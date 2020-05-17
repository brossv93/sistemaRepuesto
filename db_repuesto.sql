-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-05-2020 a las 23:17:01
-- Versión del servidor: 10.1.9-MariaDB
-- Versión de PHP: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_repuesto`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_stock_producto` (`n_cantidad` INT, `codigo` INT)  BEGIN
DECLARE nueva_existencia int;
        
        DECLARE cant_actual int;
        
        DECLARE actual_existencia int;
        
        SELECT accesorio_stock INTO actual_existencia FROM accesorio WHERE accesorio_id = codigo;
        
        SET nueva_existencia = actual_existencia + n_cantidad;
        
        UPDATE accesorio SET accesorio_stock = nueva_existencia WHERE accesorio_id = codigo;
        
        SELECT codigo,nueva_existencia;
        
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_detalle_temp` (`codigo` INT, `cantidad` INT, `token_user` VARCHAR(50), `precio` INT)  BEGIN 
    	DECLARE precio_actual decimal(10,2);
        DECLARE cod_productop int;
        
        
        SELECT accesorio_id INTO cod_productop FROM accesorio WHERE accesorio_id = codigo;
        
       INSERT INTO detalle_temp(token_user,accesorio_id,detalle_temp_cantidad,detalle_temp_precio)
       VALUES(token_user,cod_productop,cantidad,precio);
       
       SELECT tmp.correlativo,tmp.accesorio_id,p.accesorio_descripcion,tmp.detalle_temp_cantidad,tmp.detalle_temp_precio
       FROM detalle_temp tmp 
       INNER JOIN accesorio p
       ON tmp.accesorio_id = p.accesorio_id
       WHERE tmp.token_user = token_user;
     
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `anular_factura` (IN `cod_factura` INT)  BEGIN
            DECLARE existe_factura int;
            DECLARE registros int;
            DECLARE a int;
            
            DECLARE cod_producto int;
            DECLARE cant_producto int;
            DECLARE existencia_actual int;
            DECLARE nueva_existencia int;
            
            SET existe_factura = (SELECT COUNT(*) FROM venta f			
                                  WHERE f.venta_id=cod_factura);
            
            IF existe_factura > 0 THEN 
            	
                CREATE TEMPORARY TABLE tbl_tmp (
                	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    cod_prod BIGINT,
                    cant_prod int);
                
                SET a = 1;
                
                SET registros = (SELECT COUNT(*) FROM detalle_factura df WHERE 		   										df.venta_id = cod_factura);
                
                IF registros > 0 THEN 
                
                	INSERT INTO tbl_tmp(cod_prod,cant_prod) SELECT accesorio_id, 							cantidad_detalle_factura FROM detalle_factura df WHERE 			                   		       df.venta_id = cod_factura;
                    
                    WHILE a <= registros DO
                    
                    	SELECT cod_prod,cant_prod INTO cod_producto,cant_producto FROM tbl_tmp
                        WHERE id = a;
                		
                        SELECT p.accesorio_stock INTO existencia_actual 
                        FROM accesorio p
                        WHERE p.accesorio_id = cod_producto;
                        
                        SET nueva_existencia = existencia_actual + cant_producto;
                        
                        UPDATE accesorio p SET p.accesorio_stock = nueva_existencia 
                        WHERE p.accesorio_id = cod_producto; 
                        
                        
                        SET a=a+1;
                    
                    END WHILE;
                   	 DELETE FROM detalle_factura WHERE venta_id = cod_factura; 
                    DELETE FROM venta WHERE venta_id = cod_factura;
                    
                    
                    DROP TABLE tbl_tmp;
                    
                    SELECT * FROM venta f WHERE f.venta_id = cod_factura;
                
                END IF;
                
            ELSE	
            	
                SELECT 0 factura;
                
            END IF;
            
        END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `del_detalle_temp` (`detalle_temp_id` INT, `token_user` VARCHAR(50))  BEGIN
DELETE FROM detalle_temp WHERE correlativo = detalle_temp_id;
        
        SELECT tmp.correlativo, tmp.accesorio_id, p.accesorio_nombre,tmp.detalle_temp_cantidad,tmp.detalle_temp_precio
        FROM detalle_temp tmp
        INNER JOIN accesorio p
        ON tmp.accesorio_id = p.accesorio_id
        WHERE tmp.token_user = token_user;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `procesar_venta` (IN `iva_producto` INT, IN `token` VARCHAR(50))  BEGIN
	DECLARE factura INT;
        
        DECLARE registros INT;
        DECLARE total DECIMAL(10,2);
        
        DECLARE nueva_existencia int;
        DECLARE existencia_actual int;
        
        DECLARE tmp_cod_producto int;
        DECLARE tmp_cant_producto int;
        DECLARE a INT;
        SET a = 1;
        
 
        CREATE TEMPORARY TABLE tbl_tmp_tokenuser(
        	id BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            cod_prod BIGINT,
            cant_prod BIGINT);
            
        
        SET registros = (SELECT COUNT(*) FROM detalle_temp WHERE token_user = token);
        
        IF registros > 0 THEN 
        	
            INSERT INTO tbl_tmp_tokenuser(cod_prod,cant_prod) SELECT accesorio_id, 	             detalle_temp_cantidad FROM detalle_temp WHERE token_user = token;
            
            INSERT INTO venta(iva_producto_id,user_id)           			 VALUES(iva_producto,token);
           
            
            
            
            SET factura = LAST_INSERT_ID();
            
           
             INSERT INTO detalle_factura(venta_id,cantidad_detalle_factura, precio_detalle_factura,accesorio_id)
            SELECT (factura) as nroFactura,detalle_temp_cantidad,detalle_temp_precio, accesorio_id 
            FROM detalle_temp
            WHERE token_user = token;
			
            WHILE a <= registros DO 
           
           
            	SELECT cod_prod, cant_prod INTO tmp_cod_producto,tmp_cant_producto 
                FROM tbl_tmp_tokenuser 
                WHERE id =a;
                
                SELECT accesorio_stock INTO existencia_actual 
                FROM accesorio 
                WHERE accesorio_id = tmp_cod_producto;
                
                SET nueva_existencia = existencia_actual - tmp_cant_producto;
                UPDATE accesorio SET accesorio_stock = nueva_existencia 
                WHERE accesorio_id = tmp_cod_producto;
                
                SET a=a+1;
            	
            
            END WHILE; 
            
            SET total= (SELECT SUM(detalle_temp_cantidad*detalle_temp_precio) FROM detalle_temp 
                       WHERE token_user = token);
                       
             UPDATE venta SET venta_precio = total WHERE venta_id = factura;
            
            DELETE FROM detalle_temp WHERE token_user = token;
            TRUNCATE TABLE tbl_tmp_tokenuser;
            SELECT * from venta WHERE venta_id = factura;
            
            
             
        ELSE 
        	
            SELECT 0;
            
        END IF;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorio`
--

CREATE TABLE `accesorio` (
  `accesorio_id` int(11) NOT NULL,
  `accesorio_nombre` varchar(50) NOT NULL,
  `accesorio_anho` int(11) NOT NULL,
  `accesorio_descripcion` varchar(35) NOT NULL,
  `accesorio_precio` varchar(45) NOT NULL,
  `accesorio_stock` int(11) NOT NULL,
  `accesorio_fechaIng` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `accesorio_color` varchar(45) DEFAULT NULL,
  `componente_id` int(11) NOT NULL,
  `modelo_id` int(11) NOT NULL,
  `lado_id` int(11) NOT NULL,
  `lote_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `accesorio`
--

INSERT INTO `accesorio` (`accesorio_id`, `accesorio_nombre`, `accesorio_anho`, `accesorio_descripcion`, `accesorio_precio`, `accesorio_stock`, `accesorio_fechaIng`, `accesorio_color`, `componente_id`, `modelo_id`, `lado_id`, `lote_id`) VALUES
(10, 'vla1', 2020, 'valvula', '27550', 18, '2020-03-30 13:01:00', '', 1, 1, 1, 2),
(26, 'est', 2020, 'moto', '12', 8, '2020-04-07 12:29:43', NULL, 1, 1, 1, 1),
(27, 'est', 2020, 'moto', '12', 1, '2020-04-07 12:31:46', NULL, 1, 1, 1, 1),
(28, 'est', 2020, 'moto', '12', 1, '2020-04-07 12:31:46', NULL, 1, 1, 3, 1),
(29, 'moto21', 2020, 'motorva', '1500', 1, '2020-04-07 12:32:16', NULL, 1, 1, 1, 1),
(30, 'moto21', 2020, 'motorva', '1500', 1, '2020-04-07 12:32:16', NULL, 1, 1, 3, 1),
(31, 'est52', 2020, 'estrias de motor', '45', 1, '2020-04-07 12:34:13', NULL, 1, 1, 1, 1),
(32, 'est52', 2020, 'estrias de motor', '45', 1, '2020-04-07 12:34:13', NULL, 1, 1, 3, 1),
(33, 'nuevaval', 2020, 'Valvula', '', 554, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(34, 'nuevaval', 2020, 'Pistones', '', 554, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(35, 'nuevaval', 2020, 'Valvula', '', 554, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(36, 'nuevaval', 2020, 'Pistones', '', 554, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(37, 'valvun', 2020, 'Valvula', '', 1, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(38, 'valvun', 2020, 'Pistones', '', 1, '0000-00-00 00:00:00', NULL, 1, 1, 1, 1),
(39, 'newva', 2019, 'Valvula', '1500', 1, '0000-00-00 00:00:00', NULL, 1, 1, 3, 1),
(40, 'newva', 2019, 'Pistones', '2500', 1, '0000-00-00 00:00:00', NULL, 1, 1, 3, 1),
(41, 'jlk', 2020, 'Valvula', '1500', 2, '2020-04-10 14:07:46', NULL, 1, 1, 1, 1),
(42, 'jlk', 2020, 'Pistones', '2500', 1, '2020-04-10 14:07:46', NULL, 1, 1, 1, 1),
(43, 'jlk', 2020, 'Valvula', '1500', 1, '2020-04-10 16:56:59', NULL, 1, 1, 1, 1),
(44, 'jlk', 2020, 'Pistones', '2500', 1, '2020-04-10 16:56:59', NULL, 1, 1, 1, 1),
(45, 'prueba', 2020, 'Valvula', '1500', 1, '2020-04-17 11:30:08', NULL, 1, 1, 1, 1),
(46, 'prueba2', 2020, 'Valvula', '1500', 1, '2020-04-17 11:30:50', NULL, 1, 1, 1, 1),
(47, 'prueba2', 2020, 'Pistones', '2500', 1, '2020-04-17 11:30:50', NULL, 1, 1, 1, 1),
(48, 'vas', 2020, 'Valvula', '1500', 1, '2020-04-17 11:31:43', NULL, 1, 1, 1, 1),
(49, 'vas', 2020, 'Pistones', '2500', 1, '2020-04-17 11:31:43', NULL, 1, 1, 1, 1),
(50, 'kkk', 2020, 'Valvula', '1500', 3, '2020-04-17 11:33:25', NULL, 1, 1, 1, 1),
(51, 'kkk', 2020, 'Pistones', '2500', 1, '2020-04-17 11:33:25', NULL, 1, 1, 1, 1),
(52, 'kkk', 2020, 'Valvula', '1500', 1, '2020-04-18 13:08:15', NULL, 1, 1, 1, 1),
(53, 'kkk', 2020, 'Pistones', '2500', 1, '2020-04-18 13:08:15', NULL, 1, 1, 1, 1),
(54, 'pta', 2017, 'puerta', '5000', 1, '0000-00-00 00:00:00', 'Gris', 1, 1, 3, 1),
(56, 'foq', 2019, 'foquito', '100', 1, '0000-00-00 00:00:00', NULL, 3, 1, 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesorio_componente`
--

CREATE TABLE `accesorio_componente` (
  `accesorio_componente_id` int(11) NOT NULL,
  `accesorio_componente_descripcion` varchar(45) NOT NULL,
  `accesorio_componente_precio` varchar(45) DEFAULT NULL,
  `componente_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `accesorio_componente`
--

INSERT INTO `accesorio_componente` (`accesorio_componente_id`, `accesorio_componente_descripcion`, `accesorio_componente_precio`, `componente_id`) VALUES
(1, 'Valvula', '1500', 4),
(2, 'Pistones', '2500', 4),
(3, 'foquito', '100', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `componente`
--

CREATE TABLE `componente` (
  `componente_id` int(11) NOT NULL,
  `componente_descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `componente`
--

INSERT INTO `componente` (`componente_id`, `componente_descripcion`) VALUES
(1, 'Sin Componente'),
(3, 'faro'),
(4, 'Motor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_factura`
--

CREATE TABLE `detalle_factura` (
  `detalle_factura_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `cantidad_detalle_factura` int(11) NOT NULL,
  `precio_detalle_factura` int(11) NOT NULL,
  `accesorio_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_temp`
--

CREATE TABLE `detalle_temp` (
  `correlativo` int(11) NOT NULL,
  `token_user` varchar(55) NOT NULL,
  `accesorio_id` int(11) NOT NULL,
  `detalle_temp_cantidad` int(11) NOT NULL,
  `detalle_temp_precio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iva_producto`
--

CREATE TABLE `iva_producto` (
  `iva_producto_id` int(11) NOT NULL,
  `iva_producto_descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `iva_producto`
--

INSERT INTO `iva_producto` (`iva_producto_id`, `iva_producto_descripcion`) VALUES
(1, '10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lado`
--

CREATE TABLE `lado` (
  `lado_id` int(11) NOT NULL,
  `lado_descripcion` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `lado`
--

INSERT INTO `lado` (`lado_id`, `lado_descripcion`) VALUES
(1, 's/l'),
(3, 'derecho'),
(4, 'Izquierdo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `lote_id` int(11) NOT NULL,
  `lote_descripcion` varchar(45) NOT NULL,
  `lote_numero` varchar(45) NOT NULL,
  `tipoLote_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `lote`
--

INSERT INTO `lote` (`lote_id`, `lote_descripcion`, `lote_numero`, `tipoLote_id`) VALUES
(1, 'Sin Lote', '0', 1),
(2, 'otro proveedor', '546', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

CREATE TABLE `marca` (
  `marca_id` int(11) NOT NULL,
  `marca_descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`marca_id`, `marca_descripcion`) VALUES
(1, 'Suzuki');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo`
--

CREATE TABLE `modelo` (
  `modelo_id` int(11) NOT NULL,
  `modelo_descripcion` varchar(45) DEFAULT NULL,
  `marca_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modelo`
--

INSERT INTO `modelo` (`modelo_id`, `modelo_descripcion`, `marca_id`) VALUES
(1, '20000', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipolote`
--

CREATE TABLE `tipolote` (
  `tipoLote_id` int(11) NOT NULL,
  `tipoLote_descripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipolote`
--

INSERT INTO `tipolote` (`tipoLote_id`, `tipoLote_descripcion`) VALUES
(1, 'Algun tipo Lote');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_account` varchar(50) NOT NULL,
  `user_pass` varchar(35) NOT NULL,
  `user_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`user_id`, `user_account`, `user_pass`, `user_type`) VALUES
(3, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `venta_id` int(11) NOT NULL,
  `venta_precio` varchar(45) DEFAULT NULL,
  `venta_fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `iva_producto_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesorio`
--
ALTER TABLE `accesorio`
  ADD PRIMARY KEY (`accesorio_id`),
  ADD KEY `fk_item_componente1_idx` (`componente_id`),
  ADD KEY `fk_accesorio_modelo1_idx` (`modelo_id`),
  ADD KEY `fk_accesorio_lado1_idx` (`lado_id`),
  ADD KEY `fk_accesorio_lote1_idx` (`lote_id`);

--
-- Indices de la tabla `accesorio_componente`
--
ALTER TABLE `accesorio_componente`
  ADD PRIMARY KEY (`accesorio_componente_id`),
  ADD KEY `fk_accesorio_componente_componente1_idx` (`componente_id`);

--
-- Indices de la tabla `componente`
--
ALTER TABLE `componente`
  ADD PRIMARY KEY (`componente_id`);

--
-- Indices de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD PRIMARY KEY (`detalle_factura_id`),
  ADD KEY `fk_detalle_factura_accesorio1_idx` (`accesorio_id`),
  ADD KEY `fk_detalle_factura_venta1_idx` (`venta_id`);

--
-- Indices de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD PRIMARY KEY (`correlativo`,`token_user`),
  ADD KEY `fk_detalle_temp_accesorio1_idx` (`accesorio_id`);

--
-- Indices de la tabla `iva_producto`
--
ALTER TABLE `iva_producto`
  ADD PRIMARY KEY (`iva_producto_id`);

--
-- Indices de la tabla `lado`
--
ALTER TABLE `lado`
  ADD PRIMARY KEY (`lado_id`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`lote_id`),
  ADD KEY `fk_lote_tipoLote1_idx` (`tipoLote_id`);

--
-- Indices de la tabla `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marca_id`);

--
-- Indices de la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`modelo_id`),
  ADD KEY `fk_modelo_marca1_idx` (`marca_id`);

--
-- Indices de la tabla `tipolote`
--
ALTER TABLE `tipolote`
  ADD PRIMARY KEY (`tipoLote_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`venta_id`),
  ADD KEY `fk_venta_iva_producto1_idx` (`iva_producto_id`),
  ADD KEY `fk_venta_user1_idx` (`user_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesorio`
--
ALTER TABLE `accesorio`
  MODIFY `accesorio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT de la tabla `accesorio_componente`
--
ALTER TABLE `accesorio_componente`
  MODIFY `accesorio_componente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `componente`
--
ALTER TABLE `componente`
  MODIFY `componente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  MODIFY `detalle_factura_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT de la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  MODIFY `correlativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT de la tabla `iva_producto`
--
ALTER TABLE `iva_producto`
  MODIFY `iva_producto_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `lado`
--
ALTER TABLE `lado`
  MODIFY `lado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `lote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `marca`
--
ALTER TABLE `marca`
  MODIFY `marca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `modelo`
--
ALTER TABLE `modelo`
  MODIFY `modelo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipolote`
--
ALTER TABLE `tipolote`
  MODIFY `tipoLote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `venta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesorio`
--
ALTER TABLE `accesorio`
  ADD CONSTRAINT `fk_accesorio_lado1` FOREIGN KEY (`lado_id`) REFERENCES `lado` (`lado_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_accesorio_lote1` FOREIGN KEY (`lote_id`) REFERENCES `lote` (`lote_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_accesorio_modelo1` FOREIGN KEY (`modelo_id`) REFERENCES `modelo` (`modelo_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_item_componente1` FOREIGN KEY (`componente_id`) REFERENCES `componente` (`componente_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `accesorio_componente`
--
ALTER TABLE `accesorio_componente`
  ADD CONSTRAINT `fk_accesorio_componente_componente1` FOREIGN KEY (`componente_id`) REFERENCES `componente` (`componente_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_factura`
--
ALTER TABLE `detalle_factura`
  ADD CONSTRAINT `fk_detalle_factura_accesorio1` FOREIGN KEY (`accesorio_id`) REFERENCES `accesorio` (`accesorio_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detalle_factura_venta1` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`venta_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_temp`
--
ALTER TABLE `detalle_temp`
  ADD CONSTRAINT `fk_detalle_temp_accesorio1` FOREIGN KEY (`accesorio_id`) REFERENCES `accesorio` (`accesorio_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `lote`
--
ALTER TABLE `lote`
  ADD CONSTRAINT `fk_lote_tipoLote1` FOREIGN KEY (`tipoLote_id`) REFERENCES `tipolote` (`tipoLote_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `fk_modelo_marca1` FOREIGN KEY (`marca_id`) REFERENCES `marca` (`marca_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `fk_venta_iva_producto1` FOREIGN KEY (`iva_producto_id`) REFERENCES `iva_producto` (`iva_producto_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_venta_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

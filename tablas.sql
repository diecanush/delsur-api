-- Tabla: articulos
CREATE TABLE articulos (
    id_articulo INT PRIMARY KEY NOT NULL AUTO_INCREMENT ,
    descripcion TEXT,
    id_categoria INT,
    observaciones TEXT,
    precio DOUBLE
);

-- Tabla: categorias
CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre TEXT,
    observaciones TEXT
);

-- Tabla: detalles_pedidos
CREATE TABLE detalles_pedidos (
    id_item INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_pedido INT,
    id_articulo INT,
    cantidad INT
);

-- Tabla: imagenes
CREATE TABLE imagenes (
    id_imagen INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_articulo INT,
    url_imagen INT
);

-- Tabla: insumos
CREATE TABLE insumos (
    id_insumo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    descripcion TEXT,
    observaciones TEXT,
    unidad_medida TEXT
);

-- Tabla: pedidos
CREATE TABLE pedidos (
    id_pedido INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nombre_cliente TEXT,
    domicilio_envio TEXT,
    estado_pedido INT,
    fecha_envio INT,
    fecha_pedido TIMESTAMP
);

-- Tabla: producciones
CREATE TABLE producciones (
    id_produccion INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    fecha_produccion TIMESTAMP,
    observaciones TEXT
);

-- Tabla: producciones_articulos
CREATE TABLE producciones_articulos (
    id_item INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_articulo INT,
    cantidad INT,
    observaciones TEXT
);

-- Para la tabla 'articulos'
ALTER TABLE articulos
ADD INDEX idx_categoria (id_categoria),
ADD FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria);

-- Para la tabla 'detalles_pedidos'
ALTER TABLE detalles_pedidos
ADD INDEX idx_pedido (id_pedido),
ADD FOREIGN KEY (id_pedido) REFERENCES pedidos(id_pedido),
ADD INDEX idx_articulo (id_articulo),
ADD FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo);

-- Para la tabla 'imagenes'
ALTER TABLE imagenes
ADD INDEX idx_articulo (id_articulo),
ADD FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo);

-- Para la tabla 'producciones_articulos'
ALTER TABLE producciones_articulos
ADD INDEX idx_articulo (id_articulo),
ADD FOREIGN KEY (id_articulo) REFERENCES articulos(id_articulo);

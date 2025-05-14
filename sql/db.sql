CREATE TABLE IF NOT EXISTS products (
    id INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    price_base DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY(id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS variations (
    id INT NOT NULL AUTO_INCREMENT,
    product_id INT NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    additional_price DECIMAL(10,2),   
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(), 
    PRIMARY KEY(id),
    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS stocks (
    id INT NOT NULL AUTO_INCREMENT,
    variation_id INT NOT NULL,
    quantity INT,
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY(id),
    FOREIGN KEY (variation_id)
        REFERENCES variations(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS addresses (
    id INT NOT NULL AUTO_INCREMENT,
    cep VARCHAR(9) NOT NULL,
    street VARCHAR (150) NOT NULL,
    district VARCHAR (50) NOT NULL,
    city VARCHAR (50) NOT NULL,
    `state` VARCHAR (30) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY(id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS coupons (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    type_discount VARCHAR(20),
    discount_value DECIMAL(10,2),
    validity TIMESTAMP,
    minimum_subtotal DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY (id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS clients (
    id INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY(id)
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS purchase_orders (
    id INT NOT NULL AUTO_INCREMENT,
    subtotal DECIMAL(10,2),
    freight DECIMAL(10,2),
    total DECIMAL(10,2),
    `status` VARCHAR(20),
    address_id INT NOT NULL,
    client_id INT NOT NULL,
    coupon_id INT,
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY (id),
    FOREIGN KEY (address_id)
        REFERENCES `addresses`(id)
        ON DELETE CASCADE,
    FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON DELETE CASCADE,
    FOREIGN KEY (coupon_id)
        REFERENCES coupons(id)
        ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS purchase_order_items (
    id INT NOT NULL AUTO_INCREMENT,
    purchase_orders_id INT NOT NULL,
    variation_id INT NOT NULL,
    quantity INT NOT NULL,
    price_unity DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    updated_at TIMESTAMP DEFAULT NOW() ON UPDATE NOW(),
    PRIMARY KEY (id),
    FOREIGN KEY (purchase_orders_id)
        REFERENCES purchase_orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (variation_id)
        REFERENCES variations(id)
        ON DELETE CASCADE
) ENGINE=INNODB;

CREATE INDEX idx_product_id ON variations(product_id);
CREATE INDEX idx_variation_id ON stocks(variation_id);
CREATE INDEX idx_order_id ON purchase_order_items(purchase_orders_id);
CREATE INDEX idx_variation_id_purchase_order_items ON purchase_order_items(variation_id);
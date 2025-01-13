CREATE DATABASE fusion_sports;
USE fusion_sports;


CREATE TABLE clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE donos (
    id_dono INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE estabelecimentos (
    id_estabelecimento INT AUTO_INCREMENT PRIMARY KEY,
    nome_estabelecimento VARCHAR(150) NOT NULL,
    descricao TEXT,
    valor_hora decimal(10,2) DEFAULT 0.00,
    cidade VARCHAR(100),
    id_dono INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    hora_abertura_seg TIME DEFAULT NULL,
    hora_fechamento_seg TIME DEFAULT NULL,
    hora_abertura_ter TIME DEFAULT NULL,
    hora_fechamento_ter TIME DEFAULT NULL,
    hora_abertura_qua TIME DEFAULT NULL,
    hora_fechamento_qua TIME DEFAULT NULL,
    hora_abertura_qui TIME DEFAULT NULL,
    hora_fechamento_qui TIME DEFAULT NULL,
    hora_abertura_sex TIME DEFAULT NULL,
    hora_fechamento_sex TIME DEFAULT NULL,
    hora_abertura_sab TIME DEFAULT NULL,
    hora_fechamento_sab TIME DEFAULT NULL,
    hora_abertura_dom TIME DEFAULT NULL,
    hora_fechamento_dom TIME DEFAULT NULL,
    foto_principal VARCHAR(255) DEFAULT NULL,
    foto_adicional_1 VARCHAR(255) DEFAULT NULL,
    foto_adicional_2 VARCHAR(255) DEFAULT NULL,
    foto_adicional_3 VARCHAR(255) DEFAULT NULL,
    foto_adicional_4 VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (id_dono) REFERENCES donos(id_dono)
);


CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_estabelecimento INT,
    id_cliente INT,
    data_reserva DATE,
    horario_inicio TIME,
    horario_fim TIME,
    status VARCHAR(50),
    FOREIGN KEY (id_estabelecimento) REFERENCES estabelecimentos(id_estabelecimento),
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
);


CREATE DATABASE IF NOT EXISTS bd_mundo;
USE bd_mundo;

-- Tabela de usuários do sistema (autenticação)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tentativas_falhas INT NOT NULL DEFAULT 0,
    bloqueado TINYINT(1) NOT NULL DEFAULT 0,
    primeiro_acesso TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de logs de autenticação (histórico de acessos e eventos de segurança)
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NULL,
    email_tentativa VARCHAR(150),
    acao VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE TABLE continentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    populacao BIGINT,
    area_km2 DECIMAL(15,2),
    total_paises INT
);

CREATE TABLE governantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    partido_politico VARCHAR(100),
    data_nascimento DATE,
    idade INT,
    data_inicio_mandato DATE,
    data_fim_mandato DATE
);

CREATE TABLE paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    id_continente INT,
    id_governante INT,
    populacao BIGINT,
    area_km2 DECIMAL(15,2),
    idioma VARCHAR(50),
    clima VARCHAR(50),
    regime_politico VARCHAR(100),
    moeda VARCHAR(50),
    FOREIGN KEY (id_continente) REFERENCES continentes(id) ON DELETE SET NULL,
    FOREIGN KEY (id_governante) REFERENCES governantes(id) ON DELETE SET NULL
);

CREATE TABLE cidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    id_pais INT NOT NULL,
    id_governante INT,
    populacao BIGINT,
    area_km2 DECIMAL(15,2),
    clima VARCHAR(50),
    data_fundacao DATE,
    FOREIGN KEY (id_pais) REFERENCES paises(id) ON DELETE CASCADE,
    FOREIGN KEY (id_governante) REFERENCES governantes(id) ON DELETE SET NULL
);
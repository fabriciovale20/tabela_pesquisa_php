-- Criando banco de dados
CREATE DATABASE tabela_equipamentos;

-- Criando tabela dos equipamentos
CREATE TABLE `tabela_equipamentos`.`equipamentos` (`id` INT NOT NULL AUTO_INCREMENT , `equipamento` VARCHAR(100) NOT NULL , `marca` VARCHAR(100) NOT NULL , `modelo` VARCHAR(100) NOT NULL , `padrao` VARCHAR(100) NOT NULL , `valor` FLOAT(20) NOT NULL , `imagem` VARCHAR(100) NOT NULL , `situacao` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
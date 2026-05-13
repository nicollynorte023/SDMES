-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/04/2026 às 20:44
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdifcataguases`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `Login` text NOT NULL,
  `Senha` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`Login`, `Senha`) VALUES
('2030', '000'),
('02', '123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `Nome` text NOT NULL,
  `Turma` text NOT NULL,
  `Serie` text NOT NULL,
  `CelularResponsavel` text NOT NULL,
  `Matricula` text NOT NULL,
  `senha` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`Nome`, `Turma`, `Serie`, `CelularResponsavel`, `Matricula`, `senha`) VALUES
('Nic', 'ds', '4', '2030', '2112', '000'),
('eloá marques correia', 'administração', 'formou', '(27)988496315', '90', 'farofinhadosertao123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_saida`
--

CREATE TABLE `entrada_saida` (
  `entrada` time NOT NULL,
  `saida` time DEFAULT NULL,
  `matricula` text NOT NULL,
  `dia` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `entrada_saida`
--

INSERT INTO `entrada_saida` (`entrada`, `saida`, `matricula`, `dia`) VALUES
('14:43:17', '14:47:23', '2112', '2026-04-25'),
('14:48:24', '14:48:45', '2026', '2026-04-25'),
('14:50:12', '14:50:43', '2026', '2026-04-25'),
('00:00:00', '12:01:46', '2026', '2026-04-25'),
('15:20:59', '15:21:13', '2112', '2026-04-25'),
('12:16:19', '12:16:25', '2026', '2026-04-26'),
('12:16:44', '12:16:56', '2026', '2026-04-26'),
('12:17:52', '12:17:54', '2026', '2026-04-26'),
('13:16:48', '13:16:52', '2112', '2026-04-26'),
('13:29:35', '13:29:54', '2112', '2026-04-26'),
('13:51:34', '13:52:00', '2112', '2026-04-26'),
('13:57:46', '13:58:01', '90', '2026-04-26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `responsaveis`
--

CREATE TABLE `responsaveis` (
  `cpf` text NOT NULL,
  `numero` text NOT NULL,
  `senha` text NOT NULL,
  `aluno` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`Matricula`(50));
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

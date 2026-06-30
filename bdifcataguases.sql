-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/06/2026 às 22:32
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

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
('2030', '00'),
('02', '123'),
('123', 'amoe');

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
('N', 'Desenvolvimento de Sistemas', 'primeira', '(32)985096202', '11', 'amoeba023'),
('eloa', 'administração', 'primeira', '(27)988496315', '13', 'amoeba023'),
('nicgames', 'Desenvolvimento de Sistemas', '3', '(32)985096202', '167', '00'),
('eloa norte', 'Desenvolvimento de Sistemas', '2026/02', '27988496315', '20231IAM', '4080'),
('Nicolly', 'Desenvolvimento de Sistemas', 'primeira', '(32)98509-6202', '2112', 'amoeba');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `descricao` text NOT NULL,
  `email` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `status_demanda` varchar(20) NOT NULL DEFAULT 'Em Andamento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `chat`
--

INSERT INTO `chat` (`id`, `nome`, `descricao`, `email`, `data_envio`, `status_demanda`) VALUES
(2, 'elo', 'etnho', 'nicollynortesantiago@gmail.com', '2026-06-30 16:55:42', 'Concluída');

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
('13:57:46', '13:58:01', '90', '2026-04-26'),
('20:52:58', '20:53:11', '2112', '2026-06-23'),
('20:54:07', '20:54:18', '2112', '2026-06-23'),
('20:54:28', '20:54:32', '2112', '2026-06-23'),
('21:00:28', '21:00:48', '13', '2026-06-23'),
('15:51:53', '15:52:19', '13', '2026-06-27'),
('16:00:47', '16:01:05', '20231IAM', '2026-06-27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `responsaveis`
--

CREATE TABLE `responsaveis` (
  `id` int(11) NOT NULL,
  `cpf` text NOT NULL,
  `numero` text NOT NULL,
  `senha` text NOT NULL,
  `aluno` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `responsaveis`
--

INSERT INTO `responsaveis` (`id`, `cpf`, `numero`, `senha`, `aluno`) VALUES
(4, '13578627637', '(32)985096202', 'amoeba', '2112'),
(9, '13578627637', '(32)985096202', 'amoeba', '13'),
(10, '13578627637', '27988496315', '123', '20231IAM');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`Matricula`(50));

--
-- Índices de tabela `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `responsaveis`
--
ALTER TABLE `responsaveis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `responsaveis`
--
ALTER TABLE `responsaveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

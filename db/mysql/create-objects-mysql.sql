
CREATE DATABASE eom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- CREATE USER 'app'@'localhost' IDENTIFIED BY 'ncc1701#M';
CREATE USER 'app'@'localhost' IDENTIFIED BY 'ncc1701#M';
GRANT ALL PRIVILEGES ON *.* TO 'app'@'localhost' WITH GRANT OPTION;
CREATE USER 'app'@'%' IDENTIFIED BY 'ncc1701#M';
GRANT ALL PRIVILEGES ON *.* TO 'app'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
-- allowPublicKeyRetrieval=true

-- filiais

CREATE TABLE empresas(
    id bigint NOT NULL AUTO_INCREMENT,
    cnpj varchar(18),
    razao_social varchar(128),
    nome_fantasia varchar(128),
    codigo varchar(10) NOT NULL,
    CONSTRAINT PK_empresas PRIMARY KEY (id),
    CONSTRAINT UK_empresas_unique UNIQUE KEY (codigo)
);

INSERT INTO empresas (nome_fantasia,codigo) VALUES ('MATRIZ','MATRIZ');

-- contas

CREATE TABLE contas (
    id bigint NOT NULL AUTO_INCREMENT,
    empresa_id bigint NOT NULL DEFAULT 1,
    nome varchar(128),
    operacao varchar(1),
    ativa boolean NOT NULL DEFAULT 1,
    CONSTRAINT PK_contas PRIMARY KEY (id),
    CONSTRAINT FK_contas_empresas FOREIGN KEY (empresa_id) 
        REFERENCES empresas(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE;

);

INSERT INTO contas (nome,operacao,ativa) VALUES ('(Padrão) #N/C','',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('(Padrão) Entradas','C',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('(Padrão) Saídas','D',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('Caixa','C',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('Notas Fiscais','D',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('Outras Compras','D',1);
INSERT INTO contas (nome,operacao,ativa) VALUES ('Custo Fixo','D',1);

-- contas_padrao

CREATE TABLE contas_padrao (
    id bigint NOT NULL AUTO_INCREMENT,
    conta_credito_id bigint DEFAULT NULL,
    conta_debito_id bigint DEFAULT NULL,
    CONSTRAINT PK_contas_padrao PRIMARY KEY (id),
    CONSTRAINT FK_conta_credito_padrao FOREIGN KEY (conta_credito_id) REFERENCES contas (id),
    CONSTRAINT FK_conta_debito_padrao FOREIGN KEY (conta_debito_id) REFERENCES contas (id)
);

INSERT INTO contas_padrao(conta_credito_id,conta_debito_id) VALUES (2,3);

CREATE TABLE fornecedores (
    id bigint NOT NULL AUTO_INCREMENT,
    cpf_cnpj varchar(18) not null,
    razao_social varchar(255) not null,
    nome_fantasia varchar(128) not null,
    CONSTRAINT PK_fornecedores PRIMARY KEY (id)
);
INSERT INTO fornecedores (cpf_cnpj,razao_social,nome_fantasia) VALUES ('(#N/D)','(#N/D)','(#N/D)')

-- movimentos

CREATE TABLE movimentos (
    id bigint NOT NULL AUTO_INCREMENT,
    documento varchar(50),
    data_emissao date NOT NULL,
    vencimento date NOT NULL,
    valor decimal(10,2) NOT NULL,
    descricao varchar(255),
    pagamento date DEFAULT NULL,
    conta_id bigint NOT NULL DEFAULT 1,
    fornecedor_id bigint NOT NULL DEFAULT 1,
    obs long,
    CONSTRAINT PK_movimentos 
        PRIMARY KEY (id),
    CONSTRAINT FK_movimentos_conta 
        FOREIGN KEY (conta_id) 
        REFERENCES contas (id)
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    CONSTRAINT FK_movimentos_fornecedor 
        FOREIGN KEY (fornecedor_id) 
        REFERENCES fornecedores (id),
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
);

CREATE OR REPLACE VIEW vw_movimentos AS
SELECT
    m.id,
    m.vencimento,
    m.pagamento,
    m.valor,
    c.operacao AS tipo,
    c.nome AS conta,
    m.data_emissao AS emissao,
    m.documento,
    f.nome_fantasia AS fornecedor,
    m.descricao,
    CASE 
	    WHEN m.pagamento IS NOT NULL
	    THEN 0 -- 'Quitada'
    	WHEN m.vencimento < CURDATE() AND ISNULL(m.pagamento)
    	THEN 1 -- 'Vencida'
    	WHEN m.vencimento = CURDATE() 
    	THEN 2 -- 'Hoje'
    	WHEN m.vencimento = DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
    	THEN 3 -- 'Amanhã'
    	WHEN WEEKOFYEAR(m.vencimento)=WEEKOFYEAR(CURDATE()) 
    	THEN 4 -- 'Nesta Semana'
    	WHEN WEEKOFYEAR(m.vencimento)=WEEKOFYEAR(DATE_ADD(CURDATE(),interval 7 DAY))
    	THEN 5 -- 'Proxima Semana'
    	ELSE 6 -- 'Em Breve'
    END AS vence,
    m.vencimento < CURDATE() AND ISNULL(m.pagamento) AS vencida,
    e.codigo AS empresa,
    m.obs,
    m.conta_id,
    m.fornecedor_id
FROM
    movimentos m,
    contas c,
    fornecedores f, 
    empresas e
WHERE 
    c.id = m.conta_id AND 
    f.id = m.fornecedor_id AND
    e.id = c.empresa_id;

CREATE OR REPLACE VIEW vw_totais AS
    SELECT 
        YEAR(m.vencimento)       AS ano,
        MONTH(m.vencimento)      AS mes,
        DAY(m.vencimento)        AS dia,
        c.nome                   AS conta,
        c.operacao               AS tipo,
        SUM(COALESCE(m.valor,0)) AS total
    FROM
        movimentos m,
        contas c
    WHERE 
        c.id = m.conta_id
    GROUP BY
        YEAR(vencimento),
        MONTH(m.vencimento),
        DAY(m.vencimento),
        c.nome,
        c.operacao;

CREATE OR REPLACE VIEW vw_totais_diarios AS
    SELECT
        YEAR(m.vencimento)  AS ano,
        MONTH(m.vencimento) AS mes,
        DAY(m.vencimento)   AS dia,
        SUM(CASE WHEN c.operacao = 'C' THEN m.valor ELSE 0 END)    AS creditos,
        SUM(CASE WHEN c.operacao = 'D' THEN m.valor ELSE 0 END)*-1 AS debitos,
        SUM(CASE WHEN c.operacao = 'C' THEN m.valor ELSE 0 END)-
        SUM(CASE WHEN c.operacao = 'D' THEN m.valor ELSE 0 END)    AS saldo,
        SUM(CASE WHEN c.operacao = ''  THEN m.valor ELSE 0 END)    AS indefinido
    FROM
        movimentos m
    INNER JOIN
        contas c ON c.id = m.conta_id
    GROUP BY
        YEAR(m.vencimento),
        MONTH(m.vencimento),
        DAY(m.vencimento);

CREATE OR REPLACE VIEW vw_totais_mensais AS
    SELECT
        YEAR(m.vencimento)  AS ano,
        MONTH(m.vencimento) AS mes,
        SUM(CASE WHEN c.operacao = 'C' THEN m.valor ELSE 0 END)    AS creditos,
        SUM(CASE WHEN c.operacao = 'D' THEN m.valor ELSE 0 END)*-1 AS debitos,
        SUM(CASE WHEN c.operacao = 'C' THEN m.valor ELSE 0 END)-
        SUM(CASE WHEN c.operacao = 'D' THEN m.valor ELSE 0 END)    AS saldo,
        SUM(CASE WHEN c.operacao = ''  THEN m.valor ELSE 0 END)    AS indefinido
    FROM
        movimentos m
    INNER JOIN
        contas c ON c.id = m.conta_id
    GROUP BY
        YEAR(m.vencimento),
        MONTH(m.vencimento);
        
CREATE OR REPLACE VIEW vw_contas_mensal AS
SELECT
    c.empresa_id,
    YEAR(m.vencimento) AS ano,
    c.nome             AS conta,
    SUM(CASE WHEN MONTH(m.vencimento) = 01 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "janeiro",
    SUM(CASE WHEN MONTH(m.vencimento) = 02 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "fevereiro",
    SUM(CASE WHEN MONTH(m.vencimento) = 03 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "marco",
    SUM(CASE WHEN MONTH(m.vencimento) = 04 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "abril",
    SUM(CASE WHEN MONTH(m.vencimento) = 05 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "maio",
    SUM(CASE WHEN MONTH(m.vencimento) = 06 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "junho",
    SUM(CASE WHEN MONTH(m.vencimento) = 07 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "julho",
    SUM(CASE WHEN MONTH(m.vencimento) = 08 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "agosto",
    SUM(CASE WHEN MONTH(m.vencimento) = 09 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "setembro",
    SUM(CASE WHEN MONTH(m.vencimento) = 10 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "outubro",
    SUM(CASE WHEN MONTH(m.vencimento) = 11 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "novembro",
    SUM(CASE WHEN MONTH(m.vencimento) = 12 THEN CASE WHEN c.operacao='D' THEN -m.valor ELSE m.valor END ELSE 0 END) AS "dezembro",
    SUM(CASE WHEN c.operacao='D'                                         THEN -m.valor ELSE m.valor END           ) AS "total"
FROM
    movimentos m
INNER JOIN 
    contas c ON c.id = m.conta_id
GROUP BY
    c.empresa_id,
    YEAR(vencimento),
    c.nome;
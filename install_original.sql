CREATE TABLE tb_local ( 
	ci_local INT(20) UNSIGNED NOT NULL auto_increment,
	
	cd_usuario_owner int(20) UNSIGNED NOT NULL,
	cd_usuario_edit int(20) UNSIGNED,
			
	nm_local varchar(100) NOT NULL, 
	ds_local text NOT NULL,
	ds_contato varchar(200) NOT NULL, 
	
	nr_cep char(8) NOT NULL, 
	nm_rua varchar(200) NOT NULL,
	nr_rua_numero varchar(10) NOT NULL,
	nm_bairro varchar(200),
	nr_lat varchar(100) NOT NULL, 
	nr_lng varchar(100) NOT NULL, 
	
	nr_view INT(30) not null default 2,
	
	fl_ativo boolean NOT NULL DEFAULT False, 
	
	dt_create timestamp NOT NULL,
	dt_edit timestamp NOT NULL,
	dt_last_view timestamp NOT NULL,
	
	PRIMARY KEY(ci_local)
);

CREATE TABLE tb_modalidade ( 
	ci_modalidade INT(20) UNSIGNED NOT NULL auto_increment,
	nm_modalidade varchar(150) NOT NULL, 
	ds_modalidade text,
	PRIMARY KEY(ci_modalidade)
);

CREATE TABLE tb_local_modalidade (
	ci_local_modalidade INT(20) UNSIGNED NOT NULL auto_increment,
	cd_local int(20) UNSIGNED NOT NULL,
	cd_modalidade int(20) UNSIGNED NOT NULL,
  PRIMARY KEY(ci_local_modalidade)
);


CREATE TABLE tb_test ( 
	ci_test INT(20) UNSIGNED NOT NULL auto_increment,
	nr_lat DECIMAL(18,16) NOT NULL,
	nr_lng DECIMAL(18,16) NOT NULL,
	PRIMARY KEY(ci_test)
);

insert into tb_modalidade(nm_modalidade) values('Alimentos');
insert into tb_modalidade(nm_modalidade) values('Atividades Lúdicas');
insert into tb_modalidade(nm_modalidade) values('Bazar');
insert into tb_modalidade(nm_modalidade) values('Brinquedos');
insert into tb_modalidade(nm_modalidade) values('Cabelo');
insert into tb_modalidade(nm_modalidade) values('Dinheiro');
insert into tb_modalidade(nm_modalidade) values('Leite Materno');
insert into tb_modalidade(nm_modalidade) values('Material de Higiêne');
insert into tb_modalidade(nm_modalidade) values('Material de Limpeza');
insert into tb_modalidade(nm_modalidade) values('Material Escolar');
insert into tb_modalidade(nm_modalidade) values('Material Hospitalar');
insert into tb_modalidade(nm_modalidade) values('Remédios');
insert into tb_modalidade(nm_modalidade) values('Sangue');
insert into tb_modalidade(nm_modalidade) values('Serviços');
insert into tb_modalidade(nm_modalidade) values('Vestuário');
insert into tb_modalidade(nm_modalidade) values('Visitação');


###################################################################
################################################ Controle de Acesso
###################################################################

CREATE TABLE tb_usuario ( 
	ci_usuario INT(20) UNSIGNED NOT NULL auto_increment,
	tp_nivelacesso smallint NOT NULL DEFAULT 1, 
	nm_usuario varchar(150) NOT NULL, 
	nm_login varchar(50) NOT NULL, 
	nm_senha varchar(250) NOT NULL, 
	ds_email varchar(150) NOT NULL, 
	fl_sexo smallint NOT NULL DEFAULT 1, 
	fl_atualizousenha boolean NOT NULL DEFAULT False, 
	fl_ativo boolean NOT NULL DEFAULT False, 
	dt_cadastro timestamp NOT NULL , 
	dt_acesso timestamp, 
	PRIMARY KEY(ci_usuario), 
	CONSTRAINT unq_tb_usuario_nm_login UNIQUE(nm_login), 
	CONSTRAINT unq_tb_usuario_ds_email UNIQUE(ds_email) 
);

CREATE TABLE tb_transacao (
	ci_transacao INT(20) UNSIGNED NOT NULL auto_increment,
	nm_transacao varchar(50) NOT NULL,
	nm_label varchar(100) NOT NULL,
	tp_tipo int NOT NULL DEFAULT 1,
	ds_descricao varchar(100),
  PRIMARY KEY(ci_transacao)
);

CREATE TABLE tb_grupo (
	ci_grupo INT(20) UNSIGNED NOT NULL auto_increment,
	nm_grupo varchar(50) NOT NULL,
	ds_descricao varchar(100),
	dt_cadastro timestamp NOT NULL ,
  PRIMARY KEY(ci_grupo)
);

CREATE TABLE tb_grupo_transacao (
	ci_grupo_transacao INT(20) UNSIGNED NOT NULL auto_increment,
	cd_grupo int(20) UNSIGNED NOT NULL,
	cd_transacao int(20) UNSIGNED NOT NULL,
	fl_inserir char(1) NOT NULL DEFAULT 'N',
	fl_alterar char(1) NOT NULL DEFAULT 'N',
	fl_deletar char(1) NOT NULL DEFAULT 'N',
	dt_cadastro timestamp NOT NULL ,	
  PRIMARY KEY(ci_grupo_transacao)
);

CREATE TABLE tb_usuario_grupo (
	ci_usuario_grupo INT(20) UNSIGNED NOT NULL auto_increment,
	cd_usuario int(20) UNSIGNED NOT NULL,
	cd_grupo int(20) UNSIGNED NOT NULL,
  PRIMARY KEY(ci_usuario_grupo)
);

CREATE TABLE tb_foto (
	ci_foto INT(20) UNSIGNED NOT NULL auto_increment,
	cd_local int(20) UNSIGNED NOT NULL,
	cd_usuario int(20) UNSIGNED NOT NULL,
	ds_path varchar(250) not null,
	ds_hash varchar(250) not null,
	dt_data timestamp NOT NULL,
  PRIMARY KEY(ci_foto)
);

ALTER TABLE tb_grupo_transacao ADD CONSTRAINT Ref_tb_grupo_transacao_to_tb_transacao FOREIGN KEY (cd_transacao)
	REFERENCES tb_transacao(ci_transacao)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE tb_grupo_transacao ADD CONSTRAINT Ref_tb_grupo_transacao_to_tb_grupo FOREIGN KEY (cd_grupo)
	REFERENCES tb_grupo(ci_grupo)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE tb_usuario_grupo ADD CONSTRAINT Ref_tb_usuario_grupo_to_tb_grupo FOREIGN KEY (cd_grupo)
	REFERENCES tb_grupo(ci_grupo)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE tb_usuario_grupo ADD CONSTRAINT Ref_tb_usuario_grupo_to_tb_usuario FOREIGN KEY (cd_usuario)
	REFERENCES tb_usuario(ci_usuario)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE tb_foto ADD CONSTRAINT Ref_tb_foto_to_tb_usuario FOREIGN KEY (cd_usuario)
	REFERENCES tb_usuario(ci_usuario)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;

ALTER TABLE tb_foto ADD CONSTRAINT Ref_tb_foto_to_tb_local FOREIGN KEY (cd_local)
	REFERENCES tb_local(ci_local)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
	
ALTER TABLE tb_local ADD CONSTRAINT Ref_tb_local_to_tb_usuario_owner FOREIGN KEY (cd_usuario_owner)
	REFERENCES tb_usuario(ci_usuario)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
	
ALTER TABLE tb_local ADD CONSTRAINT Ref_tb_local_to_tb_usuario_edit FOREIGN KEY (cd_usuario_edit)
	REFERENCES tb_usuario(ci_usuario)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;
	
ALTER TABLE tb_local_modalidade ADD CONSTRAINT Ref_tb_local_modalidade_to_tb_local FOREIGN KEY (cd_local)
	REFERENCES tb_local(ci_local)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;	
	
ALTER TABLE tb_local_modalidade ADD CONSTRAINT Ref_tb_local_modalidade_to_tb_modalidade FOREIGN KEY (cd_modalidade)
	REFERENCES tb_modalidade(ci_modalidade)
	MATCH SIMPLE
	ON DELETE RESTRICT
	ON UPDATE RESTRICT;	

	
insert into tb_usuario(tp_nivelacesso, nm_usuario, nm_login, nm_senha, ds_email, fl_ativo)
values(2, 'USUÁRIO ADMINISTRADOR', 'ADMIN', md5('123'), 'admin@admin.com', true);
insert into tb_usuario(tp_nivelacesso, nm_usuario, nm_login, nm_senha, ds_email, fl_ativo)
values(3, 'USUÁRIO MASTER', 'MASTER', md5('123'), 'master@master.com', true);
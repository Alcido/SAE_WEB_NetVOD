drop table if exists serie;
create table  serie(
                       id int(11)auto_increment,
                       titre varchar(256) not null ,
                       descriptif text,
                       img varchar(256),
                       annee int(5),
                       date_ajout date not null,
                       genre varchar(128),
                       public varchar(128),
                       primary key (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists episode;
create table episode(
                        id int(11)auto_increment,
                        numero int(11) not null,
                        titre varchar(256) not null,
                        resume text,
                        duree int(11),
                        file varchar(256) not null,
                        serie_id int(11) not null,
                        img varchar(256),
                        PRIMARY KEY (`id`),
                        UNIQUE KEY file (`file`),
                        CONSTRAINT fk_EPISODE_id FOREIGN KEY (`serie_id`) REFERENCES `serie` (`id`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists utilisateur;
create table utilisateur(
                            id int(11) auto_increment,
                            pseudo varchar(256) not null,
                            email varchar(256) not null,
                            password varchar(256) not null,
                            role int(5),
                            primary key (id),
                            unique key email (email)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
;

drop table if exists prefSerie2User;
create table prefSerie2User(
                               id_user int(11),
                               id_serie int(11),
                               primary key (id_user,id_serie),
                               constraint fk_UTILISATEUR_id_serie foreign key (id_user) REFERENCES utilisateur (id) on delete cascade,
                               constraint fk_SERIE_id foreign key (id_serie) references serie (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists prefGenre2User;
create table prefGenre2User(
                               id_user int(11),
                               genre varchar(128),
                               primary key (id_user,genre),
                               constraint fk_UTILISATEUR_id_genre foreign key (id_user) REFERENCES utilisateur (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists enCours2User;
create  table  enCours2User(
                               id_user int(11),
                               id_serie int (11),
                               id_ep int(11),
                               primary key (id_user,id_serie,id_ep),
                               constraint fk_UTILISATEUR_id_enCOurs foreign key (id_user) REFERENCES utilisateur (id) on delete cascade,
                               constraint fk_SERIE_id_enCours foreign key (id_serie) references serie (id) on delete cascade,
                               constraint fk_EPISODE_id_enCours foreign key (id_ep) references episode (id) on delete cascade

)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


drop table  if exists notation;
create  table  notation(
                           id_user int(11),
                           id_serie int (11),
                           date_comm date,
                           commentaire text,
                           note int(5),
                           constraint fk_UTILISATEUR_id_Note foreign key (id_user) REFERENCES utilisateur (id) on delete cascade,
                           constraint fk_SERIE_id_Note foreign key (id_serie) references serie (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


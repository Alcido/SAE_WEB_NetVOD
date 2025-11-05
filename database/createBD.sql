drop table if exists serie;
create table  serie(
                       id int(11)auto_increment,
                       titre varchar(256) not null ,
                       descriptif text,
                       img varchar(256),
                       annee int(5),
                       date_ajout date not null,
                       genre varchar(128),
                       type_public varchar(128),
                       primary key (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `serie` (`id`, `titre`, `descriptif`, `img`, `annee`, `date_ajout`, `genre`, `type_public`) VALUES
                                                                                                            (1,'Le lac aux mystères',	'C\'est l\'histoire d\'un lac mystérieux et plein de surprises. La série, bluffante et haletante, nous entraine dans un labyrinthe d\'intrigues époustouflantes. A ne rater sous aucun prétexte !','',2020,'2022-10-30', null, null),
                                                                                                            (2,'L\'eau a coulé','Une série nostalgique qui nous invite à revisiter notre passé et à se remémorer tout ce qui s\'est passé depuis que tant d\'eau a coulé sous les ponts.','',1907,'2022-10-29', null, null),
                                                                                                            (3,'Chevaux fous','Une série sur la vie des chevals sauvages en liberté. Décoiffante.','',2017,'2022-10-31', null, null),
                                                                                                            (4,'A la plage','Le succès de l\'été 2021, à regarder sans modération et entre amis.','',2021,'2022-11-04', null, null),
                                                                                                            (5,'Champion','La vie trépidante de deux champions de surf, passionnés dès leur plus jeune age. Ils consacrent leur vie à ce sport. ','',2022,'2022-11-03', null, null),
                                                                                                            (6,'Une ville la nuit','C\'est beau une ville la nuit, avec toutes ces voitures qui passent et qui repassent. La série suit un livreur, un chauffeur de taxi, et un insomniaque. Tous parcourent la grande ville une fois la nuit venue, au volant de leur véhicule.','',2017,'2022-10-31', null, null);


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
                        CONSTRAINT fk_EPISODE_id FOREIGN KEY (`serie_id`) REFERENCES `serie` (`id`) ON DELETE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `episode` (`id`, `numero`, `titre`, `resume`, `duree`, `file`, `serie_id`, `img`) VALUES
                                                                                                  (1,	1,	'Le lac',	'Le lac se révolte ',	8,	'lake.mp4',	1,''),
                                                                                                  (2,	2,	'Le lac : les mystères de l\'eau trouble',	'Un grand mystère, l\'eau du lac est trouble. Jack trouvera-t-il la solution ?',	8,	'lake.mp4',	1, ''),
                                                                                                  (3,	3,	'Le lac : les mystères de l\'eau sale',	'Un grand mystère, l\'eau du lac est sale. Jack trouvera-t-il la solution ?',	8,	'lake.mp4',	1, ''),
                                                                                                  (4,	3,	'Le lac : les mystères de l\'eau chaude',	'Un grand mystère, l\'eau du lac est chaude. Jack trouvera-t-il la solution ?',	8,	'lake.mp4',	1, ''),
                                                                                                  (5,	3,	'Le lac : les mystères de l\'eau froide',	'Un grand mystère, l\'eau du lac est froide. Jack trouvera-t-il la solution ?',	8,	'lake.mp4',	1, ''),
                                                                                                  (6,	1,	'Eau calme',	'L\'eau coule tranquillement au fil du temps.',	15,	'water.mp4',	2, ''),
                                                                                                  (7,	2,	'Eau calme 2',	'Le temps a passé, l\'eau coule toujours tranquillement.',	15,	'water.mp4',	2, ''),
                                                                                                  (8,	3,	'Eau moins calme',	'Le temps des tourments est pour bientôt, l\'eau s\'agite et le temps passe.',	15,	'water.mp4',	2, ''),
                                                                                                  (9,	4,	'la tempête',	'C\'est la tempête, l\'eau est en pleine agitation. Le temps passe mais rien n\'y fait. Jack trouvera-t-il la solution ?',	15,	'water.mp4',	2, ''),
                                                                                                  (10,	5,	'Le calme après la tempête',	'La tempête est passée, l\'eau retrouve son calme. Le temps passe et Jack part en vacances.',	15,	'water.mp4',	2, ''),
                                                                                                  (11,	1,	'les chevaux s\'amusent',	'Les chevaux s\'amusent bien, ils ont apportés les raquettes pour faire un tournoi de badmington.',	7,	'horses.mp4',	3, ''),
                                                                                                  (12,	2,	'les chevals fous',	'- Oh regarde, des beaux chevals !!\r\n- non, des chevaux, des CHEVAUX !\r\n- oh, bin ça alors, ça ressemble drôlement à des chevals ?!!?',	7,	'horses.mp4',	3, ''),
                                                                                                  (13,	3,	'les chevaux de l\'étoile noire',	'Les chevaux de l\'Etoile Noire débrquent sur terre et mangent toute l\'herbe !',	7,	'horses.mp4',	3, ''),
                                                                                                  (14,	1,	'Tous à la plage',	'C\'est l\'été, tous à la plage pour profiter du soleil et de la mer.',	18,	'beach.mp4',	4, ''),
                                                                                                  (15,	2,	'La plage le soir',	'A la plage le soir, il n\'y a personne, c\'est tout calme',	18,	'beach.mp4',	4, ''),
                                                                                                  (16,	3,	'La plage le matin',	'A la plage le matin, il n\'y a personne non plus, c\'est tout calme et le jour se lève.',	18,	'beach.mp4',	4, ''),
                                                                                                  (17,	1,	'champion de surf',	'Jack fait du surf le matin, le midi le soir, même la nuit. C\'est un pro.',	11,	'surf.mp4',	5, ''),
                                                                                                  (18,	2,	'surf détective',	'Une planche de surf a été volée. Jack mène l\'enquête. Parviendra-t-il à confondre le brigand ?',	11,	'surf.mp4',	5, ''),
                                                                                                  (19,	3,	'surf amitié',	'En fait la planche n\'avait pas été volée, c\'est Jim, le meilleur ami de Jack, qui lui avait fait une blague. Les deux amis partagent une menthe à l\'eau pour célébrer leur amitié sans faille.',	11,	'surf.mp4',	5, ''),
                                                                                                  (20,	1,	'Ça roule, ça roule',	'Ça roule, ça roule toute la nuit. Jack fonce dans sa camionnette pour rejoindre le spot de surf.',	27,	'cars-by-night.mp4',	6, ''),
                                                                                                  (21,	2,	'Ça roule, ça roule toujours',	'Ça roule la nuit, comme chaque nuit. Jim fonce avec son taxi, pour rejoindre Jack à la plage. De l\'eau a coulé sous les ponts. Le mystère du Lac trouve sa solution alors que les chevaux sont de retour après une virée sur l\'Etoile Noire.',	27,	'cars-by-night.mp4',	6, '');

drop table if exists utilisateur;
create table utilisateur(
                            id int(11) auto_increment,
                            pseudo varchar(256) not null,
                            email varchar(256) not null,
                            password varchar(256) not null,
                            role int(5),
                            verifie boolean not null,
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
                               num_ep int(11),
                               primary key (id_user,id_serie,num_ep),
                               constraint fk_UTILISATEUR_id_enCOurs foreign key (id_user) REFERENCES utilisateur (id) on delete cascade,
                               constraint fk_SERIE_id_enCours foreign key (id_serie) references serie (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


drop table if exists notation;
create  table  notation(
                           id_user int(11),
                           id_serie int (11),
                           date_comm date,
                           commentaire text,
                           note int(5),
                           constraint fk_UTILISATEUR_id_Note foreign key (id_user) REFERENCES utilisateur (id) on delete cascade,
                           constraint fk_SERIE_id_Note foreign key (id_serie) references serie (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

drop table if exists serieFinie2User;
create table serieFinie2User (
                                 id_user int(11),
                                 id_serie int(11),
                                 primary key (id_user,id_serie),
                                 constraint fk_serieFinie1 foreign key (id_user) references utilisateur(id),
                                 constraint fk_serieFinie2 foreign key (id_serie) references serie(id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
drop table if exists profil;
create table profil(
                       id int(11),
                       nom varchar(128),
                       prenom varchar(128),
                       genre varchar(128),
                       birth_date date NULL,
                       adresse varchar(128),
                       primary key (id),
                       constraint fk_id_user foreign key (id) references utilisateur (id) on delete cascade
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




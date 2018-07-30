
--
-- aprè ajouter la classe Certif
-- modifié le 12/07/2018
--

CREATE TABLE certif (
id INT AUTO_INCREMENT NOT NULL, 
user_id INT NOT NULL, 
titre VARCHAR(255) DEFAULT NULL, 
path VARCHAR(255) DEFAULT NULL, 
INDEX IDX_EC509872A76ED395 (user_id), 
PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE certif ADD CONSTRAINT FK_EC509872A76ED395 FOREIGN KEY (user_id) REFERENCES atl_user (id);
--
-- modifié le nom de la table certif vers atl_certif
-- modifié le 13/07/2018
--
CREATE TABLE atl_certif (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, INDEX IDX_7FAB809AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE atl_certif ADD CONSTRAINT FK_7FAB809AA76ED395 FOREIGN KEY (user_id) REFERENCES atl_user (id);

--
-- modifié les dates de début et de fin des ateliers
-- modifié le 29/07/2018
--
UPDATE atl_event
    SET date_debut = '2019-05-25 15:19:02',
        date_fin = '2019-05-31 20:00:00'
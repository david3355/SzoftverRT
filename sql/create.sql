-- -----------------------------------------------------
-- Table `fejleszt_erp`.`objects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`objects` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`objects` (
  `id` INT NOT NULL,
  `class` VARCHAR(100) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  PRIMARY KEY (`id`))
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`ugyfel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`ugyfel` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`ugyfel` (
  `id` INT NOT NULL,
  `azonosito` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `nev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `cim_irszam` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `cim_varos` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `cim_utca_hsz` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `telefon` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `azonosito_UNIQUE` (`azonosito` ASC),
  CONSTRAINT `ugyfel_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`felhasznalo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`felhasznalo` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`felhasznalo` (
  `id` INT NOT NULL,
  `nev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `jelszo` VARCHAR(64) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `jog` TINYINT(1) NOT NULL DEFAULT 0,
  `aktiv` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nev_UNIQUE` (`nev` ASC),
  CONSTRAINT `felhasznalo_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`penztar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`penztar` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`penztar` (
  `id` INT NOT NULL,
  `megnevezes` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `egyenleg` FLOAT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `penztar_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`penztar_tetel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`penztar_tetel` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`penztar_tetel` (
  `id` INT NOT NULL,
  `penztar_fk` INT NOT NULL,
  `sorszam` INT NOT NULL,
  `megnevezes` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `osszeg` FLOAT NOT NULL,
  `datum` DATE NOT NULL,
  `storno` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `penztar_tetel_penztar_idx` (`penztar_fk` ASC),
  CONSTRAINT `penztar_tetel_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `penztar_tetel_penztar`
    FOREIGN KEY (`penztar_fk`)
    REFERENCES `fejleszt_erp`.`penztar` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`szamla`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`szamla` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`szamla` (
  `id` INT NOT NULL,
  `sorszam_elotag` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `sorszam_szam` INT(10) NOT NULL,
  `kiallito_neve` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `kiallito_cim` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `kiallito_adoszam` VARCHAR(13) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `kiallito_bszla` VARCHAR(26) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `befogado_nev` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `befogado_cim` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `befogado_adoszam` VARCHAR(13) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `befogado_bszla` VARCHAR(26) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `fizetesi_mod` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `kiallitas_datum` DATE NULL,
  `teljesites_datum` DATE NULL,
  `fizetes_datum` DATE NULL,
  `megjegyzes` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `szamla_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`szamlatomb`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`szamlatomb` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`szamlatomb` (
  `id` INT NOT NULL,
  `megnevezes` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `szamla_elotag` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `szamla_kezdoszam` INT(10) NOT NULL,
  `lezaras_datum` DATE NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `szamlatomb_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`szamla_tetel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`szamla_tetel` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`szamla_tetel` (
  `id` INT NOT NULL,
  `szamla_fk` INT NOT NULL,
  `vamtarifaszam` VARCHAR(20) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `megnevezes` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `mennyiseg_egyseg` VARCHAR(10) CHARACTER SET 'utf8' COLLATE 'utf8_general_ci' NOT NULL,
  `mennyiseg` FLOAT NOT NULL,
  `afa` FLOAT NOT NULL,
  `netto_ar` FLOAT NOT NULL,
  `brutto_ar` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `szamla_tetel_szamla_idx` (`szamla_fk` ASC),
  CONSTRAINT `szamla_tetel_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `szamla_tetel_szamla`
    FOREIGN KEY (`szamla_fk`)
    REFERENCES `fejleszt_erp`.`szamla` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;


-- -----------------------------------------------------
-- Table `fejleszt_erp`.`szamla_kifizetes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `fejleszt_erp`.`szamla_kifizetes` ;

CREATE TABLE IF NOT EXISTS `fejleszt_erp`.`szamla_kifizetes` (
  `id` INT NOT NULL,
  `kifizetes_datum` DATE NOT NULL,
  `osszeg` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `szamla_kifizetes_object`
    FOREIGN KEY (`id`)
    REFERENCES `fejleszt_erp`.`objects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
;

CREATE TABLE special_characters (
  id INT NOT NULL AUTO_INCREMENT,
  word VARCHAR(255),
  PRIMARY KEY (id)
);

INSERT INTO special_characters (word) VALUES("hola"), ("ñoño"), ("áéíóú"), ("cháchó");
SELECT * FROM special_characters;

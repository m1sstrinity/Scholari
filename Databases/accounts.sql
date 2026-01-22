CREATE TABLE mytable(
   id               INTEGER  NOT NULL PRIMARY KEY 
  ,email            VARCHAR(45) NOT NULL
  ,password         VARCHAR(64) NOT NULL
  ,password_changed BIT  NOT NULL
  ,created_at       VARCHAR(30)
);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (1,'student1@example.com','9614922f41d11c077c5da4c6eafc10ca981d6a096208078db5ed065141a9ec97',1,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (2,'threeniteacamille@gmail.com','7e036af29b992fafd7bb2e9f5fc7827fe6b70808fefae469b60f4497910b9aca',1,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (3,'rosalestrinity0625@gmail.com','9614922f41d11c077c5da4c6eafc10ca981d6a096208078db5ed065141a9ec97',1,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (4,'rosalestrinity0625@gmail.commrybync@gmail.com','c31864d1d3ee505837b6862049e80a133aacac3f0c823a5276387de178fd6b55',0,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (5,'mrybync@gmail.com','c31864d1d3ee505837b6862049e80a133aacac3f0c823a5276387de178fd6b55',0,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (6,'embeeabrantes@gmail.com','c31864d1d3ee505837b6862049e80a133aacac3f0c823a5276387de178fd6b55',0,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (7,'ssrazor28@gmail.com','0a5d17d3b19f82f8340d3977609aa9e86b4ad8b9bd71bd9eced9271f1d5b2e4a',1,NULL);
INSERT INTO mytable(id,email,password,password_changed,created_at) VALUES (8,'capalaranzaneallyson@gmail.com','c31864d1d3ee505837b6862049e80a133aacac3f0c823a5276387de178fd6b55',0,NULL);

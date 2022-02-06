DROP DATABASE IF EXISTS blog_magali ;
CREATE DATABASE blog_magali;
CREATE TABLE blog_magali.contact (
                         id INT AUTO_INCREMENT NOT NULL,
                         message TEXT NOT NULL,
                         name VARCHAR(255) NOT NULL,
                         email VARCHAR(255) NOT NULL,
                         date DATETIME NOT NULL,
                         PRIMARY KEY (id)
);


CREATE TABLE blog_magali.role (
                      id INT AUTO_INCREMENT NOT NULL,
                      name VARCHAR(50) NOT NULL,
                      PRIMARY KEY (id)
);


CREATE TABLE blog_magali.user (
                      id INT AUTO_INCREMENT NOT NULL,
                      username VARCHAR(100) NOT NULL,
                      email VARCHAR(100) NOT NULL,
                      login VARCHAR(50) NOT NULL,
                      password VARCHAR(255) NOT NULL,
                      validation_token VARCHAR(255) NOT NULL,
                      user_confirmed BOOLEAN DEFAULT false NOT NULL,
                      admin_validated BOOLEAN DEFAULT false NOT NULL,
                      created_at DATETIME NOT NULL,
                      role INT NOT NULL,
                      PRIMARY KEY (id)
);


CREATE UNIQUE INDEX user_name_idx
    ON blog_magali.user
        ( username );

CREATE TABLE blog_magali.user_admin (
                            user INT NOT NULL,
                            admin INT NOT NULL,
                            PRIMARY KEY (user, admin)
);


CREATE UNIQUE INDEX user_admin_idx
    ON blog_magali.user_admin
        ( user );

CREATE TABLE blog_magali.post (
                      id INT AUTO_INCREMENT NOT NULL,
                      title VARCHAR(255) NOT NULL,
                      header TEXT NOT NULL,
                      content TEXT NOT NULL,
                      author INT NOT NULL,
                      published BOOLEAN DEFAULT false NOT NULL,
                      published_at DATETIME,
                      created_at DATETIME NOT NULL,
                      updated_at DATETIME,
                      PRIMARY KEY (id)
);


CREATE TABLE blog_magali.comment (
                         id INT AUTO_INCREMENT NOT NULL,
                         content TEXT NOT NULL,
                         post INT NOT NULL,
                         author INT NOT NULL,
                         created_at DATETIME NOT NULL,
                         admin_validated BOOLEAN DEFAULT false NOT NULL,
                         PRIMARY KEY (id)
);


ALTER TABLE blog_magali.user ADD CONSTRAINT role_user_fk
    FOREIGN KEY (role)
        REFERENCES blog_magali.role (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.post ADD CONSTRAINT user_post_fk
    FOREIGN KEY (author)
        REFERENCES blog_magali.user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.user_admin ADD CONSTRAINT user_user_admin_valid_fk
    FOREIGN KEY (user)
        REFERENCES blog_magali.user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.user_admin ADD CONSTRAINT user_user_admin_valid_fk1
    FOREIGN KEY (admin)
        REFERENCES blog_magali.user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.comment ADD CONSTRAINT user_comment_fk
    FOREIGN KEY (author)
        REFERENCES blog_magali.user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.comment ADD CONSTRAINT post_comment_fk
    FOREIGN KEY (post)
        REFERENCES blog_magali.post (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;


INSERT INTO blog_magali.role (name) VALUES ('superadmin'), ('admin');
INSERT INTO blog_magali.user (username, email, login, password, validation_token, created_at, role) VALUES ('magali', 'magali@thuaire.fr', 'magali', 'password', 'validation token', '2022-01-31 09:00:00', 1);
INSERT INTO blog_magali.post (title, header, content, author, published, published_at, created_at, updated_at)
VALUES
    ('title1', 'header1', 'content1', 1, true, '2022-02-01 10:00:00', '2022-02-01 09:00:00', '2022-02-01 11:00:00'),
    ('title2', 'header2', 'content2', 1, true, '2022-03-01 10:00:00', '2022-03-01 09:00:00', '2022-03-01 11:00:00'),
    ('title3', 'header3', 'content3', 1, false, '2022-04-01 10:00:00', '2022-04-01 09:00:00', '2022-04-01 11:00:00'),
    ('title4', 'header4', 'content4', 1, true, '2022-05-01 10:00:00', '2022-05-01 09:00:00', '2022-05-01 11:00:00'),
    ('title5', 'header5', 'content5', 1, false, '2022-06-01 10:00:00', '2022-06-01 09:00:00', '2022-06-01 11:00:00'),
    ('title6', 'header6', 'content5', 1, true, '2022-07-01 10:00:00', '2022-07-01 09:00:00', '2022-07-01 11:00:00')
;
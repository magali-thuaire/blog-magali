
CREATE TABLE Role (
                id INT NOT NULL,
                name VARCHAR(50) NOT NULL,
                PRIMARY KEY (id)
);


CREATE TABLE User (
                id INT NOT NULL,
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
 ON User
 ( username );

CREATE TABLE user_admin_valid (
                user INT NOT NULL,
                admin_valid INT NOT NULL,
                PRIMARY KEY (user, admin_valid)
);


CREATE TABLE Post (
                id INT NOT NULL,
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


CREATE TABLE Comment (
                id INT NOT NULL,
                content TEXT NOT NULL,
                post INT NOT NULL,
                author INT NOT NULL,
                created_at DATETIME NOT NULL,
                admin_validated BOOLEAN DEFAULT false NOT NULL,
                PRIMARY KEY (id)
);


ALTER TABLE User ADD CONSTRAINT role_user_fk
FOREIGN KEY (role)
REFERENCES Role (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE Post ADD CONSTRAINT user_post_fk
FOREIGN KEY (author)
REFERENCES User (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE user_admin_valid ADD CONSTRAINT user_user_admin_valid_fk
FOREIGN KEY (user)
REFERENCES User (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE user_admin_valid ADD CONSTRAINT user_user_admin_valid_fk1
FOREIGN KEY (admin_valid)
REFERENCES User (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE Comment ADD CONSTRAINT user_comment_fk
FOREIGN KEY (author)
REFERENCES User (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE Comment ADD CONSTRAINT post_comment_fk
FOREIGN KEY (post)
REFERENCES Post (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;
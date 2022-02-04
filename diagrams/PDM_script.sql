
CREATE TABLE contact (
                         id INT AUTO_INCREMENT NOT NULL,
                         message TEXT NOT NULL,
                         name VARCHAR(255) NOT NULL,
                         email VARCHAR(255) NOT NULL,
                         date DATETIME NOT NULL,
                         PRIMARY KEY (id)
);


CREATE TABLE role (
                      id INT NOT NULL,
                      name VARCHAR(50) NOT NULL,
                      PRIMARY KEY (id)
);


CREATE TABLE user (
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
    ON user
        ( username );

CREATE TABLE user_admin (
                            user INT NOT NULL,
                            admin INT NOT NULL,
                            PRIMARY KEY (user, admin)
);


CREATE TABLE post (
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


CREATE TABLE comment (
                         id INT NOT NULL,
                         content TEXT NOT NULL,
                         post INT NOT NULL,
                         author INT NOT NULL,
                         created_at DATETIME NOT NULL,
                         admin_validated BOOLEAN DEFAULT false NOT NULL,
                         PRIMARY KEY (id)
);


ALTER TABLE user ADD CONSTRAINT role_user_fk
    FOREIGN KEY (role)
        REFERENCES role (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE post ADD CONSTRAINT user_post_fk
    FOREIGN KEY (author)
        REFERENCES user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE user_admin ADD CONSTRAINT user_user_admin_valid_fk
    FOREIGN KEY (user)
        REFERENCES user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE user_admin ADD CONSTRAINT user_user_admin_valid_fk1
    FOREIGN KEY (admin)
        REFERENCES user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE comment ADD CONSTRAINT user_comment_fk
    FOREIGN KEY (author)
        REFERENCES user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE comment ADD CONSTRAINT post_comment_fk
    FOREIGN KEY (post)
        REFERENCES post (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;
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


CREATE TABLE blog_magali.user (
                      id INT AUTO_INCREMENT NOT NULL,
                      username VARCHAR(100) NOT NULL,
                      email VARCHAR(100) NOT NULL,
                      password VARCHAR(255) NOT NULL,
                      validation_token VARCHAR(255) NOT NULL,
                      user_confirmed BOOLEAN DEFAULT false NOT NULL,
                      admin_validated BOOLEAN DEFAULT false NOT NULL,
                      created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                      role VARCHAR(20) NOT NULL,
                      PRIMARY KEY (id)
);


CREATE UNIQUE INDEX email_idx
    ON blog_magali.user
        ( email );


CREATE TABLE blog_magali.post (
                      id INT AUTO_INCREMENT NOT NULL,
                      title VARCHAR(255) NOT NULL,
                      header TEXT NOT NULL,
                      content TEXT NOT NULL,
                      author INT NOT NULL,
                      published BOOLEAN DEFAULT false NOT NULL,
                      published_at DATETIME,
                      created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                      updated_at DATETIME,
                      PRIMARY KEY (id)
);


CREATE TABLE blog_magali.comment (
                         id INT AUTO_INCREMENT NOT NULL,
                         content TEXT NOT NULL,
                         post INT NOT NULL,
                         author VARCHAR(50) NOT NULL,
                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
                         approved BOOLEAN DEFAULT false NOT NULL,
                         PRIMARY KEY (id)
);


ALTER TABLE blog_magali.post ADD CONSTRAINT user_post_fk
    FOREIGN KEY (author)
        REFERENCES blog_magali.user (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.comment ADD CONSTRAINT post_comment_fk
    FOREIGN KEY (post)
        REFERENCES blog_magali.post (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION;


INSERT INTO blog_magali.user (username, email, password, validation_token, created_at, role, user_confirmed, admin_validated) VALUES ('magali', 'magali@thuaire.fr', '6adfb183a4a2c94a2f92dab5ade762a47889a5a1', 'validation token', '2022-01-31 09:00:00', 'ROLE_SUPERADMIN', 1, 1);
INSERT INTO blog_magali.post (title, header, content, author, published, published_at, created_at, updated_at)
VALUES
    ('title1', 'Lorem ipsum dolor sit amet', 'Curabitur non metus sagittis, feugiat ipsum at, hendrerit velit. Integer sem augue, lobortis sit amet elit a, dictum maximus libero. Duis eu ligula facilisis, ultricies libero ac, semper mi. Duis aliquet luctus velit, et laoreet ligula bibendum commodo. Donec consectetur interdum tellus nec fermentum. Maecenas non turpis laoreet, tempus quam mollis, elementum lacus. Aenean nec pretium nulla, et porttitor ex. Duis sit amet neque augue. Donec nec leo turpis. Duis id risus lobortis, aliquet dolor consequat, volutpat purus.', 1, true, '2022-02-01 10:00:00', '2022-02-01 09:00:00', null),
    ('title2', 'Nulla pretium, enim non tristique pellentesque', 'Vestibulum libero lacus, scelerisque pellentesque tortor sit amet, consectetur efficitur velit. Quisque turpis massa, eleifend id lorem eu, sodales iaculis elit. Praesent sed ipsum eget erat tempus viverra. Pellentesque in metus feugiat, tempus tortor sit amet, viverra orci. Duis eleifend elit a nibh cursus, sit amet laoreet lectus bibendum. Duis ultricies blandit dui, vitae vehicula erat eleifend vel. Vivamus ac quam ultricies, feugiat nibh id, semper urna. Sed consectetur molestie sem vehicula vestibulum. Donec et metus ex. Sed tincidunt sem ac massa scelerisque congue.', 1, true, '2022-03-01 10:00:00', '2022-03-01 09:00:00', '2022-03-01 11:00:00'),
    ('title3', 'Maecenas pulvinar et lorem pretium vulputate', 'Nunc hendrerit libero nec tellus pretium hendrerit a id nisi. Aenean id tincidunt arcu. Phasellus dolor ante, accumsan et purus eget, suscipit sagittis nisl. Maecenas non convallis lacus, sed luctus libero. Phasellus lorem justo, faucibus varius vestibulum sit amet, finibus vel dolor. Vivamus sed aliquam urna. Maecenas convallis ullamcorper ex, ultricies lobortis lorem dignissim id. Phasellus auctor euismod tincidunt. Nulla facilisi. Cras ac tortor lectus. Integer iaculis, mauris non malesuada dictum, nisl diam feugiat orci, ac scelerisque purus enim id lectus. Morbi ut ipsum laoreet, cursus risus at, laoreet ante.', 1, false, null, '2022-04-01 09:00:00', '2022-04-01 11:00:00'),
    ('title4', 'Aenean ultricies nibh sem, mollis finibus lacus tempus eget', 'Maecenas vel consequat purus. Etiam mauris mauris, vulputate vitae quam ac, gravida luctus nunc. Cras et ornare felis, at interdum lectus. Etiam tempor mi ut diam pretium, at consequat sapien condimentum. Phasellus ac tristique metus. Etiam commodo erat vel neque porta, a commodo odio hendrerit. Pellentesque magna odio, convallis vitae finibus eget, pulvinar pretium risus. Sed porta sapien diam, ac dictum odio malesuada in. Cras eget lacus bibendum, varius erat ac, ullamcorper libero. Etiam faucibus erat id luctus euismod. Quisque congue tincidunt lacus, ut finibus augue lobortis sit amet. Integer vitae ligula ac lacus condimentum bibendum et id sem.', 1, true, '2022-05-01 10:00:00', '2022-05-01 09:00:00', '2022-05-01 11:00:00'),
    ('title5', 'Nunc purus nisl, consectetur sed posuere ac, consequat in nisl', 'Proin luctus porta ligula, eu laoreet mauris volutpat non. Nam at auctor eros, sed molestie massa. Phasellus ultricies, sem nec pharetra ullamcorper, massa nibh euismod tortor, vel elementum dui massa sed orci. Etiam pellentesque augue ac sapien tempor, ac tempor nunc hendrerit. Vivamus pulvinar odio sit amet risus suscipit, sit amet dignissim augue molestie. Quisque dictum, ligula non commodo rhoncus, magna metus pharetra augue, vel feugiat sapien massa eu arcu. Integer at eleifend diam. Sed consectetur scelerisque massa, et luctus eros lacinia sed. Mauris imperdiet volutpat porttitor. Phasellus interdum malesuada elit, id feugiat est fringilla vitae. Phasellus nec finibus nisi.', 1, false, null, '2022-06-01 09:00:00', '2022-06-01 11:00:00'),
    ('title6', 'Praesent at nulla fringilla nisi iaculis tristique sed et nibh', 'Nullam interdum consequat tortor, ut elementum arcu semper eu. Phasellus scelerisque lacus sit amet orci ullamcorper, eu pretium lacus sodales. Ut blandit arcu lacus, in gravida odio porta quis. Fusce et mollis lorem. Nam viverra fringilla ultricies. Sed ultricies ante et magna fringilla suscipit. Praesent a felis scelerisque, sodales urna nec, laoreet diam. Nunc egestas pharetra nibh. Integer eros neque, tristique eget mattis mattis, interdum et nisi.', 1, true, '2022-07-01 10:00:00', '2022-07-01 09:00:00', null)
;
INSERT INTO blog_magali.comment (content, post, author, created_at, approved)
VALUES
    ('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda aut culpa cupiditate est nam
    perspiciatis reiciendis rem sed voluptatibus! Amet architecto, aut autem cum facere officia quis reiciendis
    voluptatem?', 1, 'olivier', '2022-02-08 09:00:00', true),
    ('Aliquid commodi, distinctio dolorum eaque eius illo impedit, ipsam mollitia, nulla perferendis placeat qui!', 1, 'charlotte', '2022-02-08 09:00:00', true),
    ('Perspiciatis sit suscipit tempore velit voluptatibus! Corporis dolorum facilis quas soluta voluptas voluptatem.', 6, 'alexine', '2022-02-08 09:00:00', false),
    ('Assumenda at culpa delectus et facilis molestias officia provident saepe tenetur?', 6, 'alexine', '2022-02-08 09:00:00', true)
;
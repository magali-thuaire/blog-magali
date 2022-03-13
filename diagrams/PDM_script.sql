DROP DATABASE IF EXISTS blog_magali ;
CREATE DATABASE blog_magali;
CREATE TABLE blog_magali.contact (
                         id INT AUTO_INCREMENT NOT NULL,
                         name VARCHAR(255) NOT NULL,
                         email VARCHAR(255) NOT NULL,
                         message TEXT NOT NULL,
                         date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
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
                      created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                      published_at DATETIME,
                      updated_at DATETIME,
                      PRIMARY KEY (id)
);


CREATE TABLE blog_magali.comment (
                         id INT AUTO_INCREMENT NOT NULL,
                         content TEXT NOT NULL,
                         post INT NOT NULL,
                         username VARCHAR(50) NOT NULL,
                         created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL ,
                         approved BOOLEAN DEFAULT false NOT NULL,
                         PRIMARY KEY (id)
);


ALTER TABLE blog_magali.post ADD CONSTRAINT user_post_fk
    FOREIGN KEY (author)
        REFERENCES blog_magali.user (id)
        ON DELETE CASCADE
        ON UPDATE NO ACTION;

ALTER TABLE blog_magali.comment ADD CONSTRAINT post_comment_fk
    FOREIGN KEY (post)
        REFERENCES blog_magali.post (id)
        ON DELETE CASCADE
        ON UPDATE NO ACTION;

set GLOBAL sql_mode = "";

INSERT INTO blog_magali.user (username, email, password, validation_token, created_at, role, user_confirmed, admin_validated)
VALUES
       ('magali', 'superadmin@blog.fr', '$2y$10$UulyDq77YoY.ybMQdabGBeB8.JppgbMULf2twcT2mjPaBRsmmxgha', 'validationtoken', NOW(), 'ROLE_SUPERADMIN', 1, 1),
       ('magali', 'admin@blog.fr', '$2y$10$UulyDq77YoY.ybMQdabGBeB8.JppgbMULf2twcT2mjPaBRsmmxgha', 'validationtoken', NOW(), 'ROLE_USER', 1, 0);
INSERT INTO blog_magali.post (title, header, content, author, published, published_at, created_at, updated_at)
VALUES
    ('title1', 'Praesent at nulla fringilla nisi iaculis tristique sed et nibh', 'Nullam interdum consequat tortor, ut elementum arcu semper eu. Phasellus scelerisque lacus sit amet orci ullamcorper, eu pretium lacus sodales. Ut blandit arcu lacus, in gravida odio porta quis. Fusce et mollis lorem. Nam viverra fringilla ultricies. Sed ultricies ante et magna fringilla suscipit. Praesent a felis scelerisque, sodales urna nec, laoreet diam. Nunc egestas pharetra nibh. Integer eros neque, tristique eget mattis mattis, interdum et nisi.', 1, true, DATE_ADD(NOW(), INTERVAL -5 MONTH), DATE_ADD(NOW(), INTERVAL -5 MONTH), null),
    ('title2', 'Nunc purus nisl, consectetur sed posuere ac, consequat in nisl', 'Proin luctus porta ligula, eu laoreet mauris volutpat non. Nam at auctor eros, sed molestie massa. Phasellus ultricies, sem nec pharetra ullamcorper, massa nibh euismod tortor, vel elementum dui massa sed orci. Etiam pellentesque augue ac sapien tempor, ac tempor nunc hendrerit. Vivamus pulvinar odio sit amet risus suscipit, sit amet dignissim augue molestie. Quisque dictum, ligula non commodo rhoncus, magna metus pharetra augue, vel feugiat sapien massa eu arcu. Integer at eleifend diam. Sed consectetur scelerisque massa, et luctus eros lacinia sed. Mauris imperdiet volutpat porttitor. Phasellus interdum malesuada elit, id feugiat est fringilla vitae. Phasellus nec finibus nisi.', 1, false, null, DATE_ADD(NOW(), INTERVAL -4 MONTH), DATE_ADD(NOW(), INTERVAL -4 MONTH)),
    ('title3', 'Aenean ultricies nibh sem, mollis finibus lacus tempus eget', 'Maecenas vel consequat purus. Etiam mauris mauris, vulputate vitae quam ac, gravida luctus nunc. Cras et ornare felis, at interdum lectus. Etiam tempor mi ut diam pretium, at consequat sapien condimentum. Phasellus ac tristique metus. Etiam commodo erat vel neque porta, a commodo odio hendrerit. Pellentesque magna odio, convallis vitae finibus eget, pulvinar pretium risus. Sed porta sapien diam, ac dictum odio malesuada in. Cras eget lacus bibendum, varius erat ac, ullamcorper libero. Etiam faucibus erat id luctus euismod. Quisque congue tincidunt lacus, ut finibus augue lobortis sit amet. Integer vitae ligula ac lacus condimentum bibendum et id sem.', 1, true, DATE_ADD(NOW(), INTERVAL -3 MONTH), DATE_ADD(NOW(), INTERVAL -3 MONTH), DATE_ADD(NOW(), INTERVAL -3 MONTH)),
    ('title4', 'Maecenas pulvinar et lorem pretium vulputate', 'Nunc hendrerit libero nec tellus pretium hendrerit a id nisi. Aenean id tincidunt arcu. Phasellus dolor ante, accumsan et purus eget, suscipit sagittis nisl. Maecenas non convallis lacus, sed luctus libero. Phasellus lorem justo, faucibus varius vestibulum sit amet, finibus vel dolor. Vivamus sed aliquam urna. Maecenas convallis ullamcorper ex, ultricies lobortis lorem dignissim id. Phasellus auctor euismod tincidunt. Nulla facilisi. Cras ac tortor lectus. Integer iaculis, mauris non malesuada dictum, nisl diam feugiat orci, ac scelerisque purus enim id lectus. Morbi ut ipsum laoreet, cursus risus at, laoreet ante.', 1, false, null, DATE_ADD(NOW(), INTERVAL -2 MONTH), DATE_ADD(NOW(), INTERVAL -2 MONTH)),
    ('title5', 'Nulla pretium, enim non tristique pellentesque', 'Vestibulum libero lacus, scelerisque pellentesque tortor sit amet, consectetur efficitur velit. Quisque turpis massa, eleifend id lorem eu, sodales iaculis elit. Praesent sed ipsum eget erat tempus viverra. Pellentesque in metus feugiat, tempus tortor sit amet, viverra orci. Duis eleifend elit a nibh cursus, sit amet laoreet lectus bibendum. Duis ultricies blandit dui, vitae vehicula erat eleifend vel. Vivamus ac quam ultricies, feugiat nibh id, semper urna. Sed consectetur molestie sem vehicula vestibulum. Donec et metus ex. Sed tincidunt sem ac massa scelerisque congue.', 1, true, DATE_ADD(NOW(), INTERVAL -1 MONTH), DATE_ADD(NOW(), INTERVAL -1 MONTH), DATE_ADD(NOW(), INTERVAL -1 MONTH)),
    ('title6', 'Lorem ipsum dolor sit amet', 'Curabitur non metus sagittis, feugiat ipsum at, hendrerit velit. Integer sem augue, lobortis sit amet elit a, dictum maximus libero. Duis eu ligula facilisis, ultricies libero ac, semper mi. Duis aliquet luctus velit, et laoreet ligula bibendum commodo. Donec consectetur interdum tellus nec fermentum. Maecenas non turpis laoreet, tempus quam mollis, elementum lacus. Aenean nec pretium nulla, et porttitor ex. Duis sit amet neque augue. Donec nec leo turpis. Duis id risus lobortis, aliquet dolor consequat, volutpat purus.', 1, true, DATE_ADD(NOW(), INTERVAL -1 DAY), DATE_ADD(NOW(), INTERVAL -1 DAY), null)
;
INSERT INTO blog_magali.comment (content, post, username, created_at, approved)
VALUES
    ('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda aut culpa cupiditate est nam
    perspiciatis reiciendis rem sed voluptatibus! Amet architecto, aut autem cum facere officia quis reiciendis
    voluptatem?', 1, 'olivier', DATE_ADD(NOW(), INTERVAL 1 DAY), true),
    ('Aliquid commodi, distinctio dolorum eaque eius illo impedit, ipsam mollitia, nulla perferendis placeat qui!', 1, 'charlotte', DATE_ADD(NOW(), INTERVAL 2 DAY), true),
    ('Perspiciatis sit suscipit tempore velit voluptatibus! Corporis dolorum facilis quas soluta voluptas voluptatem.', 6, 'alexine', DATE_ADD(NOW(), INTERVAL 3 DAY), false),
    ('Assumenda at culpa delectus et facilis molestias officia provident saepe tenetur?', 6, 'alexine', DATE_ADD(NOW(), INTERVAL 4 DAY), true)
;
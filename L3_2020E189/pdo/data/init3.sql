CREATE TABLE group_users (
    fbgroup_userid INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    fb_groupid INT(11) UNSIGNED,
    FOREIGN KEY (fb_groupid) REFERENCES fb_group(fb_groupid)
);


ALTER TABLE group_users
ADD CONSTRAINT fk_user_id
FOREIGN KEY (user_id) REFERENCES users(user_id);

ALTER TABLE group_users
ADD CONSTRAINT fk_fb_groupid
FOREIGN KEY (fb_groupid) REFERENCES fb_group(fbgroup_id);
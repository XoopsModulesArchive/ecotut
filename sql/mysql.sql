# Database : ecoTut for Xoops 2.x
# --------------------------------------------------------

#
# Table structure for table `ecotu_faqcategories`
#

CREATE TABLE ecotu_faqcategories (
    catID       TINYINT(4)       NOT NULL AUTO_INCREMENT,
    name        VARCHAR(125)     NOT NULL DEFAULT '',
    description TEXT             NOT NULL,
    total       INT(11)          NOT NULL DEFAULT '0',
    uid         INT(10) UNSIGNED NOT NULL DEFAULT '0',
    mod         INT(1)           NOT NULL DEFAULT '0',
    supID       INT(10) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (catID),
    UNIQUE KEY catID (catID)
)
    ENGINE = ISAM COMMENT ='Make by Ghosteye';
# --------------------------------------------------------

#
# Table structure for table `ecotu_faqsupcat`
#

CREATE TABLE ecotu_faqsupcat (
    supID       TINYINT(4)   NOT NULL AUTO_INCREMENT,
    name        VARCHAR(125) NOT NULL DEFAULT ''' ''',
    description VARCHAR(225) NOT NULL DEFAULT ''' ''',
    PRIMARY KEY (supID)
)
    ENGINE = ISAM COMMENT ='Make by Ghosteye';
# --------------------------------------------------------

#
# Table structure for table `ecotu_faqtopics`
#

CREATE TABLE ecotu_faqtopics (
    topicID    TINYINT(4)      NOT NULL AUTO_INCREMENT,
    catID      TINYINT(4)      NOT NULL DEFAULT '0',
    question   VARCHAR(75)     NOT NULL DEFAULT '0',
    answer     TEXT            NOT NULL,
    summary    TEXT            NOT NULL,
    uid        INT(6)                   DEFAULT '1',
    submit     INT(1)          NOT NULL DEFAULT '0',
    datesub    INT(11)         NOT NULL DEFAULT '1033141070',
    counter    INT(8) UNSIGNED NOT NULL DEFAULT '0',
    TopicOrder TINYINT(4)      NOT NULL DEFAULT '0',
    PRIMARY KEY (topicID),
    UNIQUE KEY topicID (topicID),
    FULLTEXT KEY answer (answer),
    FULLTEXT KEY answer_2 (answer)
)
    ENGINE = ISAM COMMENT ='Make by Ghosteye';


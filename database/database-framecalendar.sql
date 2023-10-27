/* m_ steht für metadata column                                                                                                         */
/* c_ steht für content column                                                                                                          */
/* MySQL Defaults sind auf Application Ebene selten Defaults, sondern eher als fallbacks zu vestehen, weil die wahren Defaults auf      */
/* Applicationebene eingesetzt werden.                                                                                                  */

CREATE TABLE `configs` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when Konfigurationsstand was created  */
    `m_current`                 BOOLEAN         DEFAULT FALSE               ,   /* Ob aktueller Konfigurationsstand                     */
    `m_name`                    VARCHAR(32)     DEFAULT "(unnamed)"         ,   /* Name des Konfigurationsstandes                       */
    `c_password`                VARCHAR(255)    DEFAULT NULL                    /* If not set: null; else: hashed with password_hash()  */
);

CREATE TABLE `eventlists` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_name`                    VARCHAR(32)     DEFAULT "(unnamed)"         ,   /* Name der Eventlist                                   */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `c_default_duration`        INT             DEFAULT 3600                ,   /* Default Duration für Events in Seconds (Fallback 1h) */
    `c_default_place`           VARCHAR(64)     DEFAULT NULL                ,   /* Default Place für Events                             */
    `c_default_link`            VARCHAR(256)    DEFAULT NULL                ,   /* Default Link für Event                               */
    `c_default_link_target`     BOOLEAN         DEFAULT 1                   ,   /* Default Target für Links, 0: Same tab; 1: New tab    */ 
    `c_default_link_usage`      BOOLEAN         DEFAULT 1                   ,   /* Default Linknutzung, 0: nur text; 1: text & frame    */
    `c_default_headline`        VARCHAR(128)    DEFAULT NULL                ,   /* Default Headline für Events                          */
    `c_default_headline_frame`  VARCHAR(128)    DEFAULT NULL                ,   /* Default Optionale Headline für Frame wenn abweichend */
    `c_default_text`            VARCHAR(1024)   DEFAULT NULL                ,   /* Default Text für Events                              */
    `c_default_text_frame`      VARCHAR(1024)   DEFAULT NULL                    /* Default Optionaler Text für Frame wenn abweichend    */
);

CREATE TABLE `events` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_eventlist`               INT             DEFAULT NULL                ,   /* INDEX: eventlists.m_id                               */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `c_date`                    INT             DEFAULT 0                   ,   /* Date des Events (Unix Timestamp, Default 01.01.1970) */
    `c_duration`                INT             DEFAULT 3600                ,   /* Duration des Events in Seconds (Fallback 1h)         */
    `c_place`                   VARCHAR(64)     DEFAULT NULL                ,   /* Place des Events                                     */
    `c_link`                    VARCHAR(256)    DEFAULT NULL                ,   /* Link des Events                                      */
    `c_link_target`             BOOLEAN         DEFAULT 1                   ,   /* Target des Links, 0: Same tab; 1: New tab            */ 
    `c_link_usage`              BOOLEAN         DEFAULT 1                   ,   /* Linknutzung, 0: nur text; 1: text & frame            */
    `c_headline`                VARCHAR(128)    DEFAULT NULL                ,   /* Headline des Events                                  */
    `c_headline_frame`          VARCHAR(128)    DEFAULT NULL                ,   /* Optionale Headline für Frame wenn abweichend         */
    `c_text`                    VARCHAR(1024)   DEFAULT NULL                ,   /* Text des Events                                      */
    `c_text_frame`              VARCHAR(1024)   DEFAULT NULL                    /* Optionaler Text für Frame wenn abweichend            */
);

CREATE TABLE `eventlist_custom_columns` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_eventlist`               INT             DEFAULT NULL                ,   /* eventlists.m_id                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `m_name`                    VARCHAR(32)     DEFAULT "(unnamed)"         ,   /* Name der Custom Column                               */
    `c_default_value`           VARCHAR(64)     DEFAULT NULL                    /* Default Value der Custom Column                      */
);

CREATE TABLE `eventlist_custom_column_rows` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_ccol`                    INT             DEFAULT NULL                ,   /* eventlist_custom_columns.m_id                        */
    `m_event`                   INT             DEFAULT NULL                ,   /* events.id                                            */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `c_value`                   VARCHAR(64)     DEFAULT NULL                    /* Value der Custom Column                              */
);

CREATE TABLE `frames` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `m_name`                    VARCHAR(32)     DEFAULT "(unnamed)"         ,   /* Name des Frames                                      */
    `c_default_design`          INT             DEFAULT NULL                ,   /* Default Design, designs.id                           */
    `c_link`                    VARCHAR(256)    DEFAULT NULL                ,   /* Default/Overwrite Link                               */
    `c_link_def_or_ovr`         BOOLEAN         DEFAULT 0                   ,   /* If Link is default (0, default) or overwrite (1)     */
    `c_link_target`             BOOLEAN         DEFAULT 1                   ,   /* Target des Links, 0: Same tab; 1: New tab (default)  */
    `c_link_target_def_or_ovr`  BOOLEAN         DEFAULT 0                       /* If Target is default (0, default) or overwrite (1)   */
);

CREATE TABLE `frame_eventlists` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_frame`                   INT             DEFAULT NULL                ,   /* frames.id                                            */
    `m_eventlist`               INT             DEFAULT NULL                ,   /* eventlists.id                                        */
    `m_time_created`            INT             DEFAULT NULL                    /* Unix Timestamp when set                              */
);

CREATE TABLE `calendars` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `c_default_design`          INT             DEFAULT NULL                ,   /* Default Design, designs.id                           */
    `c_overwrite_link_target`   INT             DEFAULT 0                   ,   /* Ovrwrite Target, 0: Off; 1: Same tab;  2: New tab    */
    `c_show_days_before`        INT             DEFAULT 0                   ,   /* How many days before today are shown                 */
    `c_show_days_after`         INT             DEFAULT 30                      /* How many days after today are shown                  */
);

CREATE TABLE `calendar_eventlists` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_calendar`                INT             DEFAULT NULL                ,   /* calendar.id                                          */
    `m_eventlist`               INT             DEFAULT NULL                ,   /* eventlists.id                                        */
    `m_time_created`            INT             DEFAULT NULL                    /* Unix Timestamp when set                              */
);

CREATE TABLE `designs` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `m_name`                    VARCHAR(32)     DEFAULT "(unnamed)"         ,   /* Name des Designs                                     */
    `c_code_frame_html`         TEXT            DEFAULT NULL                ,   /* HTML Code für Frames                                 */
    `c_code_frame_css`          TEXT            DEFAULT NULL                ,   /* CSS Code für Frames                                  */
    `c_code_calendar_html`      TEXT            DEFAULT NULL                ,   /* HTML Code für Kalender                               */
    `c_code_calendar_css`       TEXT            DEFAULT NULL                    /* CSS Code für Kalender                                */
);

CREATE TABLE `design_parameter` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_design`                  INT             DEFAULT NULL                ,   /* designs.id                                           */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `m_html_or_css`             BOOLEAN         DEFAULT 0                   ,   /* 0: HTML Parameter; 1: CSS Parameter                  */
    `m_frame_or_calendar`       BOOLEAN         DEFAULT 0                   ,   /* 0: Frame Parameter; 1: Calendar Parameter            */
    `m_key`                     VARCHAR(32)     DEFAULT NULL                ,   /* Parameter Key                                        */
    `c_value`                   VARCHAR(128)    DEFAULT NULL                ,   /* Parameter Value                                      */
    `c_option`                  INT             DEFAULT -1                      /* Option as Value, -1: use value; 0+: option id        */
);

CREATE TABLE `design_parameter_options` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_parameter`               INT             DEFAULT NULL                ,   /* design_parameter.id                                  */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when set                              */
    `c_parameter_option`        VARCHAR(128)    DEFAULT NULL                    /* Parameter option                                     */
);

CREATE TABLE `shorts` (
    `m_id`                      INT             PRIMARY KEY auto_increment  ,   /*                                                      */
    `m_time_created`            INT             DEFAULT NULL                ,   /* Unix Timestamp when created                          */
    `m_time_modified`           INT             DEFAULT NULL                ,   /* Unix Timestamp when last modified                    */
    `m_key`                     VARCHAR(32)     DEFAULT NULL                ,   /* Short Key                                            */
    `c_value`                   VARCHAR(1024)   DEFAULT NULL                    /* Short Value                                          */
);

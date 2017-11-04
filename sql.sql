CREATE TABLE m_application (
    api_key character varying(36) PRIMARY KEY,
    fqdn character varying(255) NOT NULL,
    user_account character varying(255) NOT NULL,
    dyno_used bigint DEFAULT '-1'::integer NOT NULL,
    dyno_quota bigint DEFAULT '-1'::integer NOT NULL,
    update_time timestamp without time zone DEFAULT localtimestamp NOT NULL,
    select_type integer NOT NULL
);

CREATE TABLE m_access_time (
    access_time timestamp without time zone NOT NULL
);
INSERT INTO m_access_time VALUES(localtimestamp);

CREATE TABLE t_quota_history (
    history_seq bigserial PRIMARY KEY,
    update_time timestamp without time zone DEFAULT localtimestamp NOT NULL,
    quota_previous bigint NOT NULL,
    quota_current bigint NOT NULL,
    fqdn character varying(255) NOT NULL
);

CREATE TRIGGER update_trigger BEFORE UPDATE ON m_application FOR EACH ROW EXECUTE PROCEDURE set_update_time();

DROP TABLE IF EXISTS "kw_language";

CREATE TABLE "kw_language"
(
    "locale" VARCHAR (5) NOT NULL,
    "name" VARCHAR (64) NOT NULL,
    "id" VARCHAR (64) NOT NULL,
    "params" VARCHAR (256) NOT NULL,
    "text" VARCHAR (512) NOT NULL
);
CREATE INDEX "kw_text_locale_name_id_uq_idx" ON "kw_language" ("locale", "name", "id");
INSERT INTO "kw_language"("locale", "name", "id", "params", "text") VALUES ('ru', 'errors', 'update-error', '[]', 'error message: %s');
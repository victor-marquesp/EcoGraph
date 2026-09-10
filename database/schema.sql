
CREATE TABLE species (

    id INTEGER PRIMARY KEY AUTOINCREMENT,

    name VARCHAR(120) NOT NULL,
    scientific_name VARCHAR(120) UNIQUE NOT NULL,
    description TEXT NULL CHECK(length(description) < 1000)

);

CREATE TABLE interspecific_interactions (

    speciesA_id INTEGER NOT NULL,
    speciesB_id INTEGER NOT NULL,

    type VARCHAR(120) NOT NULL,
    description TEXT NULL CHECK(length(description) < 1000),

    FOREIGN KEY (speciesA_id) REFERENCES species (id),
    FOREIGN KEY (speciesB_id) REFERENCES species (id),

    PRIMARY KEY (speciesA_id, speciesB_id),

    CHECK(speciesA_id > speciesB_id)
);

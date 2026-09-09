
INSERT INTO species (name, scientific_name, description) VALUES
('Leão', 'Panthera leo', 'Grande felino social habitante da savana africana.'),
('Zebra-de-burchell', 'Equus quagga burchellii', 'Herbívoro conhecido por suas listras pretas e brancas características.'),
('Peixe-palhaço', 'Amphiprion ocellaris', 'Pequeno peixe marinho que habita em anêmonas.'),
('Anêmona-do-mar', 'Heteractis magnifica', 'Invertebrado marinho munido de tentáculos com células urticantes.');

INSERT INTO interspecific_interactions (speciesA_id, speciesB_id, type, description) VALUES
(1, 2, 'Predação', 'O leão (id 1) caça a zebra (id 2) para alimentação.'),
(3, 4, 'Mutualismo', 'O peixe-palhaço (id 3) obtém proteção na anêmona (id 4) e limpa seus tentáculos.');

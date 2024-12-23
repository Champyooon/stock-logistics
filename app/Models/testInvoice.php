                Forms\Components\Select::make('client_id')
                    ->relationship(name:'client', titleAttribute:'company_name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->required(),
                Forms\Components\TextInput::make('num_invoice')
                    ->default(random_int(0,999999))
                    ->required(),
                Forms\Components\DatePicker::make('date_invoice')
                    ->required(),
                Forms\Components\TextInput::make('designation')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('prix_unit')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('XOF'),
                Forms\Components\TextInput::make('prix_total')
                    ->numeric()
                    ->default(0)
                    ->prefix('XOF'),
                Forms\Components\TextInput::make('observation')
                    ->maxLength(255),

# lara-Support

Have useful classes  

# LaraServiceProvider
    
    mergeConfig($rootPath, $config = '', $isPublish = true)
    loadViews($rootPath, $path = '', $isPublish = true)
    loadRoutes ($rootPath, $path = '')
    runningInConsole($commands)
    
    registerFunctions($rootPath, $path = 'helpers.php')
    registerConstants($rootPath, $path = 'constants.php')
    registerSingleton($singleton, $class)
    registerSingletons($singletons)
    registerMiddleware($middleware)
    registerProviders($providers)
    registerAlias($alias, $class)
    registerAliases($aliases)
    
    getPackage($rootPath)
    getPackagePath($rootPath)
    getSrcPath($rootPath)
    getConfigPath($rootPath)
    getResourcePath($rootPath)
    getViewVendorPath($path)
    getViewPath($rootPath, $view = 'views')
    getRoutePath($rootPath, $path = 'routes.php')
# Str
    positions($string, $search)
    
        search occourence
        return empty array or associative array
            [
                occurence => position
            ]
        example 
        Str::positions('I love php, I love php too!','php')
        returns
            [
              1 => 7
              2 => 19
            ]
        Str::positions('I love php, I love php too!','Php')
        return []
            
    ipositions($string, $search)
        
        search case-insesitive simialar positions

        Str::ipositions('I love php, I love php too!','php')
            returns
                [
                  1 => 7
                  2 => 19
                ]
        Str::ipositions('I love php, I love php too!','Php')
            returns
                [
                  1 => 7
                  2 => 19
                ]
    
    
    after($subject, $search, $occurrence = 1, $caseSensitive = true)
        Str::after('I love php, I love php too!','php')    
            return ", I love php too!"
        Str::after('I love php, I love php too!','php', 2)
            return " too!"
        Str::after('I love php, I love php too!','php', 3)
            return false
        Str::after('I love php, I love php too!','php', Str::LAST)
            return " too!"
        Str::after('I love php, I love php too!','PHP', 2)
            return false
        Str::after('I love php, I love php too!','PHP', 2, false)
            return " too!"
        
        
    before
        Str::before('I love php, I love php too!','php')    
            return "I love php, I love "
        Str::before('I love php, I love php too!','PHP', 1)
            return "I love "
        Str::before('I love php, I love php too!','php', 2)
            return "I love php, I love "
        Str::before('I love php, I love php too!','php', 3)
            return false
        Str::before('I love php, I love php too!','PHP', 1)
            return false
        Str::before('I love php, I love php too!','PHP', 1, false)
            return "I love "
        
    between
    wrap
    iwrap
# DB
    getTable
        return all db tables list
    
    getColumnsFullInfo
        return list
            [
                coulumn => [
                    type        => string | int |bigint |smallint 
                    is_nullable => true | false,
                    default     => default-value | null
                    extra       => auto_increment | ''
                    length      => length (This key set when column have lenght)
                    additioanal => unsigned | zerofill 
                ]
            ]
            

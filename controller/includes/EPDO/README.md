# Méthode développement de EPDO : note pour moi même.

La classe EPDO : permet d'instancier un objet EPDO
- Chaque aspect de l'objet est traité par un getsionnaire écrit sous la forme d'un trait complétant l'objet PDO.
- Ne pas oublier : les propriété statiques ne sont accessible QUE par des méthodes statiques (même avec les traits). 
 
    Obj (EPDO)                  trait (DBHandler)
        obj -> method() { trait::method(){ trait::proprties } }



    ## constructor 
    constructor :
    ```
        $BDD = new EPDO ( array $params[] );
    ```

    ## currents methods 

    ### selectBase()
    ```
        $BDD -> selectBase ( string $dbname = null );
    ```
    **@param** : optional : string : select a base
    **@return** : string : current basename 

    ### connect()
    ```
        $BDD -> connect ( array $params[] );
    ```
    **@param** : required : array : conection config
    **@return** : void 
    It will try to create PDO with $params.


    ### unconnect()
    ```
        $BDD -> unconnect ( string $dbname );
    ```
    **@param** : required : string : base to unlink
    **@return** : void
    It will destroy PDO $dbname    


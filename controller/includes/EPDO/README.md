* Méthode développement de EPDO : note pour moi même.

La classe EPDO : permet d'instancier un objet EPDO
- Chaque aspect de l'objet est traité par un getsionnaire écrit sous la forme d'un trait complétant l'objet PDO.
- Ne pas oublier : les propriété statiques ne sont accessible QUE par des méthodes statiques (même avec les traits). 
 
    Obj (EPDO)                  trait (DBHandler)
        obj -> method() { trait::method(){ trait::proprties } }




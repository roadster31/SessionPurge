# Session Purge

A simple Thelia command to delete outdated session files from the local/sessions directory.

Run it periodically, in a cron for example, to prevent the local/session directory to grow indefinitely.

The command uses the value of the `session_config.lifetime` configuration variable, which is the session lifetime 
in seconds, and delete all files which are older than `session_config.lifetime` seconds.
If `session_config.lifetime` is 0 (zero), the command will not delete anything. Use the `--older-than _seconds_` option 
to define the maximum age of a session file. When `--older-than` is used, the value of `session_config.lifetime` configuration variable is ignored.

For example, to delete sessions older than one day, use the following command :

`Thelia sessions:purge --older-than 86400`

If you can't run a cron on your host, you can use the following URL to trigger the purge process :

`http://yourhost.tld/session-purge/<i>secret-key</i>`

The secret key is stored in the `Config/secret-key.txt`. Enter the string you want in the first line of this file, and use 
it in the URL. For example, if you entered `53cr37_k3y` in this files, you'll call the purge URL as this :

`http://yourhost.tld/session-purge/53cr37_k3y`

The default key is `Thelia2` (surprise !).

To specify a  session lifetime, use the `older_than` URL parameter, for example :

`http://yourhost.tld/session-purge/53cr37_k3y?older_than=86400`

Your can also use the `verbose=1` URL parameter to get the list of deleted files.

----

Une commande Thelia qui vous permet de supprimer les fichiers de session obsolètes du répertoire local/sessions.

Il est conseillé de la lancer régulièrement pour éviter que le répertoire local/sessions grossisse indéfiniment, au 
risque de saturer le disque, ou d'épuiser les réserves d'inodes.

La commande utilise la valeur de `session_config.lifetime`, qui est la durée de vie d'une session en secondes, et supprime
tous les fichiers qui sont plus anciens que `session_config.lifetime` secondes.
Si `session_config.lifetime` valeur 0 (zéro), la commande ne supprimera aucun fichier. Vous pouvez alors utiliser l'option
`--older-than _seconds_` pour définir l'age maximum des fichiers de session. Quand `--older-than` est utilisé, la valeur
de `session_config.lifetime` est ignorée.

Par exemple, pour supprimer les sessions datant de plus d'un jour, utiliser la commande :

`Thelia sessions:purge --older-than 86400`

Si vous ne pouvez pas lancer de cron ou de commandes sur votre serveur, vous pouvez utiliser l'URL suivante pour déclencher
 la purge :
 
`http://yourhost.tld/session-purge/<i>clef_secrete</i>`

La clef secrète est stockée dans le fichier `Config/secret-key.txt`. Entrez la chaîne de caractère que vous voulez sur la 
première ligne de ce fichier, et utilisez la dans l'URL. Par exemple, si vous avez indiqué `53cr37_k3y` dans ce fichier,
vous devrez utiliser l'URL suivante :

`http://yourhost.tld/session-purge/53cr37_k3y`

La clef par défaut est `Thelia2` (surprise !).

Pour indiquer une durée de vie de session, vous pouvez utiliser le paramètre `older_than`:

`http://yourhost.tld/session-purge/53cr37_k3y?older_than=86400`

Vous pouvez aussi utiliser le paramètre `verbose=1` pour obtenir la liste des fichiers supprimés.

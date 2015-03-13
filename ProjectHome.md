Do you remember? When you were happy that a web hosting service — free — allowed you to bring your stuff online? You started playing with this AND ... You just figured out SQL services weren't accessible from the Outside — I mean a different IP than the supplier bought. Your PHP scripts can easily enjoy your — wide — database while your other account at a different supplier (even your home server or desktop computer) can't use your not-so-shared e-data. That's annoying you just thought and continued to play your HTML avoiding the possibility to build a lot larger, huger, shared, often-backuped, limitless SQL database just for free! Are you crazy?!

Here is a way to bypass any SQL accessing restriction : a few simple PHP scripts :)

///
Those scripts AREN'T USING HTTPS|SSL but POST, it let your queries be TRAVELING IN CLEAR on da web.
Your SQL PASSWORD IS SAFE. Still, you're not protected under a man-on-the-middle attack — SNIFFERS.
///

Vous le savez peut-être, la connection au serveur SQL chez FREE (et d'autres  hébergeurs gratuits) est restreinte aux seuls serveurs de l'hébergeur. En gros, si l'on ne fait pas nos requètes SQL sur le serveur mais à partir d'une IP différente, SQL.FREE.FR (ou le serveur SQL) ne les acceptera pas. Cela fonctionne PARTOUT.

Ces scripts sont à votre disposition pour que vous puissiez effectuer toutes les requètes que vous désirez sur un serveur FREE (ou autre) à partir d'une autre adresse IP.


You may read the wiki's README! : http://code.google.com/p/distant-queries/source/browse/trunk/README

Here is an exemple : https://code.google.com/p/distant-queries/source/browse/trunk/local/local.php
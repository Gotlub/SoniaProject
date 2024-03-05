import mysql.connector

def dateFormatXX( LaDate):
    timeFormat = "2019-01-01"  #date par default a specifier dans chaque fichier  (année par default si format non compatible ) format yyyy/mm/dd !!
    if LaDate.replace("/", "").isdigit() and len(LaDate.split("/")[0]) == 2 and len(LaDate.split("/")[1]) == 2 and len(LaDate.split("/")[2]) == 4:
        time = LaDate.split("/")
        timeFormat = "-".join(time[::-1])
    return timeFormat

def adresseSpliteur(adresse):
    listeAdresse = adresse.split(" ")
    #Si le premier est de type numérique
    if listeAdresse[0].isdigit() :
        return listeAdresse[0], " ".join(listeAdresse[1:])
    #Sinon retourne un numero de voie vide
    return "", adresse



fileR = open("c:\\Projetsonia\\2019\\2019.txt", "r", encoding = "utf8") #chemin d'acces du fichier (CSV sans l'entête copier dans un fichier .txt)
connection_params= {
'host' : "localhost",
'user' : "root",
'password' :"",
'database' :"sonia"
}

lines = fileR.readlines()
for n, line in enumerate(lines) :
    line = line.replace('"', "")
    line = line.replace(",", ".")
    line = line.replace("'", "''")
    liste = line.split(";")
    timeFormat = dateFormatXX(liste[7])                                       #date_controle !!
    adresseRdvNum, adresseRdvVoie = adresseSpliteur(liste[17])                 #adresse       !!
    stringClientSelect = "select id  from client where nom = '" + liste[15] + "' and prenom = '" + liste[16] + "' LIMIT 0, 1"
    stringClientInsert = "insert into client (nom, prenom) \
                    values ('" + liste[15] + "', '" +  liste[16] + "')"
    stringAdresseSelect = "select id from adresse as a where a.numero = '"  +  adresseRdvNum + "' and a.adresse = '" +  adresseRdvVoie + "' and a.cp = '" + liste[24] + "' and a.section_cadastrale = '" + liste[12] + "' and a.commune = '" + liste[18] + "' LIMIT 0, 1"
    stringAdresseInsert = "insert into adresse (numero, adresse, cp, commune, section_cadastrale) \
                    values ('" + adresseRdvNum +  "', '" + adresseRdvVoie + "', '" +  liste[24] + "', '" + liste[18] + "', '" + liste[12] + "')"
    stringRdvInsert = "INSERT INTO  rendez_vous (client_id, adresse_id, facturation, date_facturation, commentaire, type_controle, num_dossier, date_controle, type_traitement, type_installation, rejet_inf, conformite, impact, type_rpqs, adresse_facturation, cp_facturation, commune_facturation, nom_propriaitaire, prenom_propriaitaire ) Values \n "
    with mysql.connector.connect(**connection_params) as db :
        with db.cursor() as c:
            c.execute(stringClientSelect)
            id_client = c.fetchone()
            if id_client is None :
                c.execute(stringClientInsert)
                db.commit()
                c.execute(stringClientSelect)
                id_client = c.fetchone()
            c.execute(stringAdresseSelect)
            id_adresse = c.fetchone()
            if id_adresse is None :
                c.execute(stringAdresseInsert)
                db.commit()
                c.execute(stringAdresseSelect)
                id_adresse = c.fetchone()
            listeRdv = [ id_client[0], # client_id
                        id_adresse[0], # adresse_id
                        liste[1],  # facturation
                        "",  # date_facturation
                        liste[9], # commentaire
                        liste[3], # type_controle
                        liste[4], # num_dossier
                        timeFormat,# date_controle
                        "",        # type_traitement
                        liste[19], # type_installation
                        "", # rejet_inf
                        liste[8], # conformite
                        liste[11], # impact
                        liste[10],  # type_rpqs
                        liste[23], # adresse_facturation
                        liste[24], # cp_facturation
                        liste[25], # commune_facturation
                        liste[21], # nom_propriaitaire
                        liste[22]  # prenom_propriaitaire
                            ]
            stringRdvInsert += "(" + ",".join([ "'" + str(k) + "'" for k in listeRdv]) + ")"
            c.execute(stringRdvInsert)
            db.commit()
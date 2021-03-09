/**
 * @mainpage Projekt 3
 * @link
 * proj3.h
 * @endlink
 *
 * @file proj3.h
 * @brief Projekt 3 - Jednoducha shlukova analyza
 * @author Daniel Weis <xweisd00@stud.fit.vutbr.cz>
 * @date December 2017
 */


/**
 * @brief Struktura reprezentujuca objekt s urcitymi souradnicami.
 */
struct obj_t {
	/** identifikator */
	int id;
	/** suradnice x */
	float x;
	/** suradnice y */
	float y;
};

/**
 * @brief Struktura reprezentujici shluk objektu.
 */
struct cluster_t {
	/** pocet objektov v shluku */
	int size;
	/** kapacita shlukov (pocet objektov, pre ktore je rezervovane miesto v poli) */
	int capacity;
	/** pole objektov naleziacich danemu shluku */
	struct obj_t *obj;
};


/**
 * @defgroup clusters Prace so shlukmi
 * @{
 */

/**
 * Inicializace shluku `c`, alokuje pamet pro `cap` objektu.
 * Ukazatel NULL u pole objektu znamena kapacitu 0.
 *
 * @post
 * Shluk `c` bude mat alokovanu pamat pre `cap` objketov,
 * ked nenastane chyba pri alokaci.
 *
 * @param c shluk pre inicializaciu
 * @param cap pozadovana kapacita shlukov
 */
void init_cluster(struct cluster_t *c, int cap);

/**
 * Odstraneni vsech objektu shluku `c` a inicializace na prazdny shluk.
 *
 * @post
 * Alokovana pamat pre vsetky objekty shluku `c` bude uvolnena.
 *
 * @param c shluk pre odstranenie
 */
void clear_cluster(struct cluster_t *c);

/// hodnota pre realokaciu shluku
extern const int CLUSTER_CHUNK;

/**
 * Zmena kapacity shluku 'c' na kapacitu 'new_cap'.
 *
 * @pre
 * Kapacita shluku `c` bude vacsi alebo rovna 0.
 *
 * @post
 * Kapacita shluku `c` bude zmenena na novou kapacitu `new_cap`,
 * ak nenastane chyba pri alokacii.
 *
 * @param c shluk pre zmenu kapacity
 * @param new_cap nova kapacita
 * @return shluk s novou kapacitou, v pripade chyby NULL
 */
struct cluster_t *resize_cluster(struct cluster_t *c, int new_cap);

/**
 * Prida objekt `obj` na konec shluku `c`.
 * Rozsiri shluk, pokud se do nej objekt nevejde.
 *
 * @pre
 * Pocet objektov v shluku bude vacsi alebo rovny 0.
 *
 * @post
 * Na poslednej pozicii shluku `c` bude objekt `obj`,
 * ak nenastane chyba pri alokacii.
 *
 * @param c shluk pre pridanie objektu
 * @param obj objekt, ktory bude pridany do shluku
 */
void append_cluster(struct cluster_t *c, struct obj_t obj);

/**
 * Do shluku `c1` prida objekty shluku `c2`. Shluk `c1` bude v pripade nutnosti rozsiren.
 * Objekty ve shluku `c1` budou serazny vzestupne podle identifikacniho cisla. Shluk `c2` bude nezmenen.
 *
 * @pre
 * Pocet objektov v shluku `c2` bude vetsi alebo rovny 0.
 *
 * @post
 * Shluk `c1` bude rozsireny o objekty shluku `c2`, ak nenastane chyba
 * pri alokaci.
 *
 * @post
 * Objekty v shluku `c1` budu zoradene vzostupne podla ID.
 *
 * @param c1 shluk, do ktereho budu pridane objekty shluku `c2`
 * @param c2 shluk, jeho objekty budu pridane do shluku `c1`
 */
void merge_clusters(struct cluster_t *c1, struct cluster_t *c2);

/**
 * Razeni objektu ve shluku vzestupne podle jejich identifikatoru.
 *
 * @post
 * Objekty v shluku `c` budu zoradene vzostupne podla ID.
 *
 * @param c shluk pre zoradenie.
 */
void sort_cluster(struct cluster_t *c);

/**
 * Tisk shluku `c` na stdout.
 *
 * @post
 * Objekty shluku `c` budu vypisane na stdout.
 *
 * @param c shluk pre vypis
 */
void print_cluster(struct cluster_t *c);

/**
 * @}
 */


/**
 * @defgroup array_of_clusters Prace s polem shluku
 * @{
 */

/**
 * dstrani shluk z pole shluku 'carr'. Pole shluku obsahuje 'narr' polozek (shluku).
 * Shluk pro odstraneni se nachazi na indexu 'idx'. Funkce vraci novy
 * pocet shluku v poli.
 *
 * @post
 * Z pola shlukov `carr` bude odstraneny prvok na indexe `idx`
 * a pole bude o 1 mensie.
 *
 * @param carr pole shlukov
 * @param narr pocet shlukov v poli
 * @param idx index shlukov pre odstranenie
 * @return novy pocet shlukov v poli
 */
int remove_cluster(struct cluster_t *carr, int narr, int idx);

/**
 * Pocita Euklidovskou vzdalenost mezi dvema objekty.
 *
 * @see https://en.wikipedia.org/wiki/Euclidean_distance
 *
 * @param o1 objekt 1
 * @param o2 objekt 2
 * @return Euklidovska vzdalenost mezdi objektmi `o1` a `o2`
 */
float obj_distance(struct obj_t *o1, struct obj_t *o2);

/**
 * Pocita vzdialenost dvoch shlukov.
 *
 * @pre
 * Pocet objektov v shluku `c1` bude vacsi ako 0.
 *
 * @pre
 * Pocet objektov v shluku `c2` bude vacsi ako 0.
 *
 * @param c1 shluk 1
 * @param c2 shluk 2
 * @return vzdalenost shluku `c1` a `c2`
 */
float cluster_distance(struct cluster_t *c1, struct cluster_t *c2);

/**
 * Funkce najde dva nejblizsi shluky. V poli shluku 'carr' o velikosti 'narr'
 * hleda dva nejblizsi shluky. Nalezene shluky identifikuje jejich indexy v poli
 * 'carr'. Funkce nalezene shluky (indexy do pole 'carr') uklada do pameti na
 * adresu 'c1' resp. 'c2'.
 *
 * @post
 * Indexy dvoch nejblizsich shlukov budu ulozene v `c1` a `c2`.
 *
 * @param carr pole shlukov
 * @param narr pocet shlukov v poli
 * @param c1 index jednoho z najdenych shlukov
 * @param c2 index druheho z najdenych shlukov
 */
void find_neighbours(struct cluster_t *carr, int narr, int *c1, int *c2);

/**
 * Ze souboru 'filename' nacte objekty. Pro kazdy objekt vytvori shluk a ulozi
 * jej do pole shluku. Alokuje prostor pro pole vsech shluku a ukazatel na prvni
 * polozku pole (ukalazatel na prvni shluk v alokovanem poli) ulozi do pameti,
 * kam se odkazuje parametr 'arr'. Funkce vraci pocet nactenych objektu (shluku).
 * V pripade nejake chyby uklada do pameti, kam se odkazuje 'arr', hodnotu NULL.
 *
 * @pre
 * Bude existovat soubor `filename` a program bude mat prava pre jeho citanie.
 *
 * @pre
 * Data v subore budu v spravnom formate.
 *
 * @post
 * Pro kazdy objekt uvedeny vo vstupnom subore bude vytvoreny shluk,
 * vsetky tieto shluky budu ulozene v poli shlukov `arr`, ktore bude
 * mat alokovanu pamat pre pocet shlukov uvedenych vo vstupno subore,
 * ak nenastane chyba pri alokacii.
 *
 * @param filename nazov suboru pre nacitanie objektov
 * @param arr ukazatel na pole shlukov nacitanych zo suboru (v pripade chyby bude ukazovat na NULL)
 * @return pocet nacitanych shlukov (v pripade chyby vrati -1)
 */
int load_clusters(char *filename, struct cluster_t **arr);

/**
 * Tisk pole shluku. Parametr 'carr' je ukazatel na prvni polozku (shluk).
 * Tiskne se prvnich 'narr' shluku.
 *
 * @post
 * Objekty vsetkych shlukov v poli shlukov `carr` budou vypisane na stdout.
 *
 * @param carr pole shlukov pre vypis
 * @param narr pocet shlukov v poli
 */
void print_clusters(struct cluster_t *carr, int narr);

/**
 * @}
 */

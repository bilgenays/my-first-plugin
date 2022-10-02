<?php
// Plugin Name: My First Plugin
// Plugin URI: https://www.github.com/bilgenays/my-first-plugin
// Description: Test
// Version: 0.0.1
// Author: Aysenur Akkurt
// Author: https://www.github.com/bilgenays/

add_action("admin_menu","myplugin");
function myplugin(){
    add_menu_page("My First Plugin","My First Plugin","manage_options","my-first-plugin","eklenti_icerigi");
}
function eklenti_icerigi(){ 
    $postmeta_phone = get_post_meta(14,"whatsapp_phone",true);
    $postmeta_ad = get_post_meta(15,"whatsapp_ad",true);
    ?>
        <form method="post">
        <label>Telefon Numaranız:</label><br>
        <input type="number" name="phone" value="<?php echo $postmeta_phone; ?>"><br>
        <label>İsminiz:</label><br>
        <input type="text" name="ad" value="<?php echo $postmeta_ad ?>"><br>
        <input type="submit" value="Kaydet">
        </form>
<?php 
$phone = $_POST["phone"];
$ad = $_POST["ad"];

if ($_POST){
if ( $phone != $postmeta_phone){
update_post_meta(14,"whatsapp_phone",$phone,$postmeta_phone,true);
}elseif($phone == $postmeta_phone){
    echo "Bu telefon numarası sistemde kayıtlıdır.";
}
if ( $ad != $postmeta_ad){
    update_post_meta(15,"whatsapp_ad",$ad,$postmeta_ad,true);
}elseif($ad == $postmeta_ad){
    echo "Bu isim sistemde kayıtlıdır.";
}
}
}
    // Bir text submit edildiğinde post metaya ekleme, post metayı güncelleme
    if ($_POST){
            $yeni = $_POST["ad"];
            $meta = get_post_meta(4,"ad");
            $veri = $meta[0];
            if ($yeni != $veri){
            update_post_meta(4,"ad",$yeni,$veri,true);
        }
        }
?>
<?php
// TABLO OLUSTURMA VE insert VERİ EKLEME
        function tablo_olustur(){
            global $wpdb;
            $charset = $wpdb->get_charset_collate();
            $tablo_adi = $wpdb->prefix. "bilgiler";
            $sql = "CREATE TABLE $tablo_adi(
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                isim VARCHAR(300) NOT NULL,
                telefon VARCHAR(300) NOT NULL,
                eposta VARCHAR(300) NOT NULL,
                UNIQUE KEY id (id) ) $charset;";
                require_once(ABSPATH. "wp-admin/includes/upgrade.php");
                dbDelta($sql);
                register_activation_hook(__FILE__,'creating_plugin_table');
            
        // $wpdb->insert("wp_bilgiler", array(
        //     "isim" => "aysenur",
        //     "eposta" => "aysbilgen@gmail.com",
        //     "telefon" => "01234567890",
        // ));
        
        // sayfa her yenilendiğinde wp-bilgiler'e veri eklediği için yukarıdaki 5 satırı yorum satırı haline getirdim. nasıl durdurulur? öğrenilecek. | edit: öğrenildi. if ($_POST) koşulu içerisine alabilirim.

        }
        tablo_olustur();
?>
<?php 
//Post ekle/güncelle yaparken META BOX üzerinden DB'e veri ekleme
add_action("admin_menu","kutucuk_ekle");
function kutucuk_ekle(){
add_meta_box("kutu","Bilgiler","kutucuk_goster","Post","side","high",null);
}
function kutucuk_goster(){ 
    echo '<input type="text" name="isim" placeholder="Adinizi Giriniz">';
    echo '<input type="text" name="telefon" placeholder="Telefonunuzu Giriniz">';
    echo '<input type="text" name="eposta" placeholder="Epostanizi Giriniz">';
}
add_action("save_post","kutucuk_kaydet");
function kutucuk_kaydet(){
    global $wpdb;
    $mb_isim = $_POST["isim"];
    $mb_telefon = $_POST["telefon"];
    $mb_eposta = $_POST["eposta"];
    $wpdb->insert("wp_bilgiler", array(
        "isim" => $mb_isim,
        "telefon" => $mb_telefon,
        "eposta" => $mb_eposta,
    ));
}
?>
<!-- WHATSAPP BUTONU EKLEME ÖRNEĞİ -->
<?php
add_action("wp_head","wp_button");
function wp_button(){
    $eklenti_yolu = plugin_dir_url(__FILE__);
    $postmeta_phone = get_post_meta(14,"whatsapp_phone",true);
    $postmeta_ad = get_post_meta(15,"whatsapp_ad",true);
?>
<link rel="stylesheet" href="<?php echo $eklenti_yolu. "assets/style.css"; ?>">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<!-- Açılan whatsapp yönlendirme sayfasında eklediğimiz plugin içerisine girilen bilgileri çağırıyoruz, aynı zamanda whatsapp butonuna hover yaparken sol altta görünen bağlantı adresinde de bu bilgiler yer alıyor -->
<a href="https://wa.me/<?php echo $postmeta_phone ?>?text=Merhaba <?php echo $postmeta_ad ?>! Size web siteniz üzerinden ulaştım. Bilgi almak istiyorum." target="_blank" class="wp-button">
    <i class="fa fa-whatsapp"> </i>
</a>
<?php
}
?>
<?php
require __DIR__ .  "/../../vendor/autoload.php";

use Source\Models\Unit;

function getData()
{
    $features = [];
    $unidades = new Unit();
    $listaUnidades = $unidades->find()->fetch(true);

    foreach ($listaUnidades as $unidade) {
        $features['features'][] =
            [
                'geometry' => ['type' => 'Point', 'coordinates' => [floatval($unidade->longitude), floatval($unidade->latitude)]],
                'type' => 'Featu4re',
                'properties' => ['description' => $unidade->sigla, 'title' => $unidade->nome],
            ];
    }
    return json_encode($features);
}

function setPoints()
{
    $unidades = new Unit();
    $listaUnidades = $unidades->find()->fetch(true);

    $marker = "";
    $i = 0;

    foreach ($listaUnidades as $unidade) {
        $marker = $marker . " var marker{$i} = new mapboxgl.Marker() .setLngLat([" . floatval($unidade->longitude) . "," . floatval($unidade->latitude) . "]) .addTo(map); ";
        $i++;
    }

    return $marker;
}
?>


<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoicmFmYWVsYWNpb2xpIiwiYSI6ImNrbW1hbG5yczFqMWgydm1nM2RzZ3Nnc3UifQ.Lp-FDZea6NujnXquPugyzQ';

    var geojson = {
        'type': 'FeatureCollection',
        'features': ''
    };

    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/rafaelacioli/ckn97ovto0w2n17of54d0zrmn',
        center: [-45.517417, -23.043507],
        zoom: 15,
    });

    <?php echo setPoints(); ?>
</script>
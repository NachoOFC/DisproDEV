<script charset="utf-8">
import { Bar } from 'vue-chartjs'

export default {
    extends: Bar,
    props: ['datasets'],
    mounted () {
        this.renderChart({
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: this.datasets
        });
    }
}
</script>

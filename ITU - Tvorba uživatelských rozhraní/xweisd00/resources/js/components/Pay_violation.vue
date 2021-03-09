<template>
    <div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary mt-2 mb-2 float-right" data-toggle="modal" data-target="#exampleModal">
            Kúpiť známku
        </button>     
    </div>
</template>

<script>
export default {
    name: "pay_violation",
    data() {
        return {
        }
    },
    methods: {
        get_checked: function() {
            const temp = []
            $('#violations input[type=checkbox]:checked').each(function() {
                temp.push( $(this).val() )
            })
            console.log(temp);
            // window.axios.post('/payviolation', temp);
        }
    },
};
</script>
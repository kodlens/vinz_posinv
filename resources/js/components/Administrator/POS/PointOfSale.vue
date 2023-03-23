<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop is-12-tablet">
                    <form @submit.prevent="submit">
                        <div class="box">
                            <div class="box-header">
                                POINT OF SALE
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Customer Name">
                                        <b-input type="text" v-model="fields.customer" placeholder="Customer Name"></b-input>
                                    </b-field>
                                </div>
                                <div class="column is-4">
                                    <b-field label="Sales Date">
                                        <b-datepicker v-model="fields.sales_date" placeholder="Stock In Date"></b-datepicker>
                                    </b-field>
                                </div>
                            </div>

                            <hr>
                            <div class="columns">
                                <div class="column">

                                </div>
                                <div class="column is-3">
                                    <div>
                                        <span class="total">Total: {{ totalPrice | formatToCurrency }}</span>
                                        
                                    </div>
                                </div>
                            </div>

                            <div v-for="(item, index) in fields.orders" :key="index">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Browse Item" label-position="on-border">
                                            <modal-pos-browse-item :prop-item="fields.orders[index].item_name" @browseItem="emitBrowseItem($event, index)"></modal-pos-browse-item>
                                        </b-field>
                                    </div>
                                    <div class="column is-2">
                                        <b-field label="Quantity" label-position="on-border">
                                            <b-numberinput controls-alignment="right"
                                            v-model="fields.orders[index].qty"
                                            min="0"
                                            :controls="false"
                                            expanded
                                            placeholder="Quantity"
                                            controls-position="compact"
                                            ></b-numberinput>
                                        </b-field>
                                    </div>
                                    <div class="column is-3">
                                        <b-field label="Price Each" label-position="on-border">
                                            <b-numberinput
                                                expanded
                                                controls-position="compact"
                                                controls-alignment="right"
                                                :controls="false"
                                                v-model="fields.orders[index].price" min="0" placeholder="Price Each" step="0.01"></b-numberinput>
                                        </b-field>
                                    </div>
                                    <div class="column is-1">
                                        <b-button 
                                            icon-left="delete-outline"
                                            @click="remove(index)" 
                                            type="is-danger"></b-button>
                                    </div>
                                </div> <!--cols -->
                            </div> <!--for -->

                            <div class="buttons mt-3">
                                <b-button type="is-primary"
                                    class="is-outlined is-small"
                                    @click="addRow"
                                    icon-left="plus"
                                    label="New"></b-button>
                            </div>

                            <div class="buttons is-right mt-3">
                                <button class="button is-success has-text-weight-bold"
                                    icon-right="content-save-outline">Save Order</button>
                            </div>
                        </div> <!--box -->
                    </form>

                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->


    </div>
</template>

<script>


export default{
    props: ['propId', 'propData'],
    data() {
        return{
            id: 0,
            fields: {
                sales_date: new Date(),
                customer: '',
                orders: []
            },

            itemName: '',
            errors: {},

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },
        }

    },

    methods: {

        submit: function(){
            if(this.id > 0){
                //update
                axios.put('/pos/'+this.id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/pos';
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //insert here
                this.fields.total_sales = this.totalPrice;

                axios.post('/pos', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/pos';
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        },

        emitBrowseItem: function(dataRow, ix){
            let found = false;
            let indexFound = 0

            this.fields.orders.forEach((el, index) =>{
                if(el.item_id === dataRow.item_id){
                    found = true
                    indexFound = index
                    //this.fields.orders.slice(ix, 1);
                }
            })

            if(found){
                this.fields.orders[indexFound].qty += 1
            }else{
                this.fields.orders[ix].item_id = dataRow.item_id;
                this.fields.orders[ix].item_name = dataRow.item_name;
                this.fields.orders[ix].qty = 1;
                this.fields.orders[ix].price = dataRow.srp;
            }

        },

        addRow(){
            this.fields.orders.push({
                item_id: 0,
                item_name: '',
                qty: 0,
                price: 0,
            });
        },

        remove(ix){
            this.fields.orders.splice(ix, 1);
        },


      
    },

    mounted() {
    },

    computed: {
        totalPrice(){
            let total = 0.00;
            this.fields.orders.forEach(el => {
                total += (el.qty * el.price);
            });
            return total; // 1,234
        },
    }
}


</script>


<style scoped>
    .box-header{
        font-weight: bold;
        font-size: 1.3em;
        margin: 10px 0;
        padding: 10px 0;
        border-bottom: 1px solid gray;
    }

    .total{
        font-weight: bolder;
        font-size: 2em;
    }
</style>

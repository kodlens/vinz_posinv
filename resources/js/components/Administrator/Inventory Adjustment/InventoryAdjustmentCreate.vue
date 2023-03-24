<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-8-tablet">
                    <form @submit.prevent="submit">
                        <div class="box">
                            <div class="box-header">
                                CREATE INVENTORY ADJUSTMENT
                            </div>

                            <b-field label="Stock In Date">
                                <b-datepicker v-model="fields.adjustment_date" disabled placeholder="Stock In Date"></b-datepicker>
                            </b-field>

                            <b-field label="Browse Item">
                                <modal-pos-browse-item :prop-item="itemName" @browseItem="emitBrowseItem($event)"></modal-pos-browse-item>
                            </b-field>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Adjustment Type" expanded>
                                        <b-select v-model="fields.adjustment" expanded
                                            placeholder="Adjustment Type">
                                            <option value="SUBTRACT">SUBTRACT</option>
                                            <option value="ADD">ADD</option>
                                        </b-select>
                                    </b-field>
                                </div>

                                <div class="column">
                                    <b-field label="Quantity">
                                        <b-numberinput controls-alignment="right"
                                        v-model="fields.adjusted_qty"
                                        min="0"
                                        :controls="false"
                                        expanded
                                        placeholder="Quantity"
                                        controls-position="compact"
                                        ></b-numberinput>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-input type="textarea" v-model="fields.remarks"
                                        placeholder="Adjustment reason..."></b-input>
                                </div>
                            </div>

                            <div class="buttons mt-3">
                                <button class="button is-success" icon-right="account-arrow-up-outline">SAVE</button>
                            </div>
                        </div>
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
                item_id: 0,
                adjustment : '',
                adjusted_qty: 0,
                remarks: '',
                adjustment_date: new Date(),
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
                axios.put('/inventory-adjustment/'+this.id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                window.location = '/inventory-adjustment';
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
                axios.post('/inventory-adjustment', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                window.location = '/inventory-adjustment';
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

        emitBrowseItem: function(dataRow){
            this.fields.item_id = dataRow.item_id;
            this.itemName = dataRow.item_name;
        },

        initData: function(){
            this.id = parseInt(this.propId);

            if(this.id > 0){
                let ndata = JSON.parse(this.propData);
                this.fields.item_id = ndata.item_id;
                this.fields.qty_in = ndata.qty_in;
                this.fields.price = ndata.price;
                this.fields.stock_in_date = new Date(ndata.stock_in_date);
                this.itemName = ndata.item.item_name;
            }
        },
    },

    mounted() {
       this.initData()
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
</style>

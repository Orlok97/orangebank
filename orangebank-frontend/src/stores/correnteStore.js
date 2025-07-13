import { defineStore } from "pinia";

 export const useCorrenteStore=defineStore('corrente',{
    state:()=>({
        saldo:null,
        extrato:null,
        isLoading:true
    }),
    actions:{
        async getSaldo(){
            const response=await fetch('http://127.0.0.1:8000/api/corrente/saldo',{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                }
            })
            const data= await response.json()
            if(data){
                this.saldo=data.response
                console.log(data.response)
            }

        },
        async getExtrato(){
            const response=await fetch('http://127.0.0.1:8000/api/corrente/extrato',{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                }
            })
            const data= await response.json()
            if(data){
                this.extrato=data.response
                console.log(data.response)
            }

        },
        async createDeposito(value){
            const response=await fetch('http://127.0.0.1:8000/api/corrente',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                },
                body:JSON.stringify({
                    valor:value
                })
                
            })
            if(!response.ok){
                throw new Error('erro ao depositar o valor')
            }
            const data=await response.json()
            console.log(data.response)
            this.saldo = parseFloat(this.saldo) + parseFloat(value)
            // this.saldo=this.saldo+value
            
        },
        async trasnferirSaldo(id){
            
        }      
    }
})
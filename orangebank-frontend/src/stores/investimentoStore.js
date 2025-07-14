import { defineStore } from "pinia"

export const useInvestimentoStore=defineStore('investimento',{
    state:()=>({
        saldo:null,
        extrato:null,
        isLoading:true
    }),
    actions:{
        async getSaldo(){
            const response=await fetch('http://127.0.0.1:8000/api/investimento/saldo',{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                }
            })
            const data= await response.json()
            if(data){
                this.saldo=data.response
                console.log(data.response,'from investimentoSTore')
            }

        },
        async getExtrato(){
            const response=await fetch('http://127.0.0.1:8000/api/investimento/extrato',{
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
            const response=await fetch('http://127.0.0.1:8000/api/investimento',{
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
            
        },
        async comprarAtivos(name,price){
            const response=await fetch('http://127.0.0.1:8000/api/investimento/comprar',{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                    'Authorization':`Bearer ${sessionStorage.getItem('access_token')}`
                },
                body:JSON.stringify({
                    name:name,
                    price:price
                })
                
            })
            if(!response.ok){
                throw new Error('comprar o ativo')
            }
            const data=await response.json()
            console.log(data.response)
            this.saldo = parseFloat(this.saldo) - parseFloat(price)
            
        },      
    }
})
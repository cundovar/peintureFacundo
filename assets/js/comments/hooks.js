import {useState,useEffect, useCallback} from 'react';
import axios from 'axios';
// import ReactDOM from 'react-dom/client';
import  ReactDOM  from 'react';
import { render } from 'react-dom';
import {createRoot} from 'react'
// import {createRoot} from 'react-dom/client';
import React from 'react';


async function jspnLdFetch(url,method='GET',data=null){
    const params={
        method: method,
        headers:{
            'Accept':'application/ld+json',
            'content-Type':'application/json'
        }
    }
    if(data){
        params.body=JSON.stringify(data)
    }
    const response =await fetch(url,params)
    // if (response.status=204){
    //     return null
    // }
    const dataResponse = await response.json()
    if (response.ok){ 
        return dataResponse
    }else{
        throw dataResponse
    }
}

export function Commentaire(url){
    const [loading,setLoading]=useState(false)
   const [items, setItems]=useState([])
   const[count,setCount]=useState(0)
   const [next,setNext]=useState(null)
   const load =useCallback(async () => {    
    setLoading(true) 
    try{
    const response = await jspnLdFetch(next || url)
    setItems(items=>[...items,...response[`hydra:member`]] )
    setCount(response['hydra:totalItems'])
    if (response['hydra:view'] && response['hydra:view']['hydra:next']){
        setNext(response['hydra:view']['hydra:next'])
    }else{
        setNext(null)
    }
    }catch(error){
    console.error(error )
          }
     setLoading(false)
},[url,next])
return{
items,
load,
loading,
setItems,
count,
hasMore : next != null
}

}
export function useFetch(url,method='POST',callback=null) {
    // const [error,setErrors]=useState({})
    const [loading,setLoading]=useState(false)
    const load = useCallback(async(data=null)=>{
        setLoading(true)
       

          const response= await jspnLdFetch(url,method='POST',data)
          if(callback){

              callback(response)
          }
        
        setLoading(false)
    },[url,method,callback])
    return{
        load,
        loading,
        
        
    }
}

console.log(useFetch)





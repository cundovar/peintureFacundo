import { render } from "react-dom"
import React, { useCallback, useEffect, useRef, useState } from "react"
import { Commentaire,  useFetch } from "./hooks"










const dateFormat={
    dateStyle:'medium',
    timeStyle :"short"
}

function Comments({post,user}){

    const {items:comments,load,loading,setItems:setComment,count,hasMore}=Commentaire('/api/commentaires?oeuvres='+ post)
    const addComment=useCallback(comment=>{
        setComment(comments=>[comment, ...comments])
    },[])
    useEffect(()=>{
        load()
    },[])
    return <div>
        {loading && 'Chargement...'}
        <Title count={count}/>
        { user && <CommentForm post={post}  onComment={addComment} />}
        {comments.map(comment=><Comment key={comment.id} comment={comment}/>)}
        {hasMore && <button className="btn btn-primary"onClick={load} >charger plus de commentaire</button>}
    </div>
}

const Comment=React.memo(({comment})=>{

    const date=new Date(comment.dateAt)
    return <div className="row post-comment">
        <h5 className="col-sm-3">
        {/* <strong>    {comment.user.prenom} </strong> */}
            commenté le {date.toLocaleString(undefined, dateFormat)} 
        </h5>
        <div className="clo-sm-9">
            <p>{ comment.comment} </p>
        </div>

    </div>
})

const CommentForm= React.memo(({post,onComment})=>{
    const ref=useRef(null)
     const {load,loading}= useFetch('/api/commentaires','POST',{onComment})
     console.log({load})
     const onSubmit=useCallback(e=>{
        e.prevenDefault()
        load({
            comment:ref.current.value,
            post:"/api/oeuvres"+post
        })
     },[load,ref,post])
   
     const [comment,setComment]=useState()
     console.log(ref)
    return   <div className="well">
    <form onSubmit={onSubmit}>
        {comment}

        <fieldset>
            <legend>laisser un commentaire</legend> 
   </fieldset> 
       <div className="form-group"> 
           <textarea ref={ref} 
          cols="5"
            rows="5"
             className="form-control" onChange={e=>setComment(e.target.value)}/>
        </div> 
        <div className="form-group"> 
            <button className="btn btn-primary" disabled={loading}>envoyer</button> 
        </div> 
    </form> 
</div> 
    
   
   
   
   
   
})

function Title({count}){
    return <h4 >
        <Icon icon="Comments"/>
        {count} Commentaire{count> 1 ? 's':''} </h4>
}

function Icon({icon}){
    return  <i className={"fa fa-"+ icon} aria-hidden="true"></i>
}

class CommentsElement extends HTMLElement{


    connectedCallback(){
        const post = parseInt(this.dataset.post,10)
        const user = parseInt(this.dataset.user,10) || null
        render (<Comments post={post} user={user} />,this)
        
    }

}



customElements.define('comments-show',CommentsElement)



















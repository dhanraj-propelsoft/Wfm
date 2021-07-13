import _ from 'lodash';




export const _DE_Quan =  (id) => {

    const Quantity=this.state.SelectedJobItemData;
    
    Quantity.filter(function(e) { 
     
      if(e.id === id){

        if(e.quantity===1)
        {
          return;
        }

        e.quantity=e.quantity-1;
      } 
       
    });
  
    this.setState({SelectedJobItemData:Quantity});

  }

  export const _IN_Quan=(id)=>{

    const Quantity=this.state.SelectedJobItemData;
   
    
    
    Quantity.filter(function(e) { 
     
      if(e.id === id){
        e.quantity=e.quantity+1;
      } 
      
    });
      
    this.setState({SelectedJobItemData:Quantity});

  }

  export const getItemData=(id,data_type)=>{

    const ItemData=this.state.SelectedJobItemData;
   
    if(ItemData.length==0)
    {
      return 1;
    }
    
    let obj = _.find(ItemData, function (obj) { return obj.id === id; });
   
   // console.log(obj.quantity);
   
    return obj[data_type] ;
   
  }

 export const updateItemData=(id,value)=>{
    console.log(id,value);
    const ItemData=this.state.SelectedJobItemData;
   
    
    
    ItemData.filter(function(e) { 
     
      if(e.id === id){
        e.item_status=value;
      } 
      
    });
    
    console.log(ItemData);

    this.setState({SelectedJobItemData:ItemData});

   
  }


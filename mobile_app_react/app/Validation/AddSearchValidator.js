import validate from 'validate.js';
const constraints = {
  Name:{ 

    presence: true,
    length: {
        minimum: 1,
        message: 'Is Required!',
    },
  },

  GST:{ 

    presence: true,
    length: {
        minimum: 1,
        message: 'Is Required!',
    },
  },

  Mobile:{
    
    presence:true,
//     numericality: {
//       maximum:10,
//     message: 'Is Required!',
// },
    format: {
      pattern:/^\d{10}$/,
      message: 'Number should be 10 numbers!',
    },
    length: {
      is: 10,
    }

    
    },
    State:{
        
      presence:true,
      numericality: {
      message: 'Is Required!',
    },
      },
    City:{
        
      presence:true,
      numericality: {
      message: 'Is Required!',
    },

    
    },
    assignedTo:{
        
      presence:true,
      numericality: {
      
        message: 'Is Required!',
    },

    
    },
    createDate:{
      presence: {message: "is required"},
    },
    endDate:{
        
      presence: {message: "is required"},
    }
}




const validator = (field, value) => {
  // Creates an object based on the field name and field value
  // e.g. let object = {email: 'email@example.com'}
  let object = {}
  object[field] = value

  let constraint = constraints[field]
  console.log(object, constraint)

  // Validate against the constraint and hold the error messages
  const result = validate(object, { [field]: constraint })
  console.log(object, constraint, result)

  // If there is an error message, return it!
  if (result) {
    // Return only the field error message if there are multiple
    return result[field][0]
  }

  return null
}

export default validator;
# Sample code

Just a small sampling of some of my latest code. Some parts may be truncated for privacy reasons or otherwise

### Laravel
- `CSV Import Controller` - for handling user csv import/exports
- Custom class for generating `Form` elements, such as text, checkboxes, textareas, etc
- `Login controller` - this handles the login process
  - User logs in, and is sent a token to their email. They use this to log in
- `University Controller/Validator` - This handles the CRUD functions for a "University"


### React
- This is part of a multi step registration form for users
- Data is saved to Firebase
- Users can save their progress/data to their own user account, and can log in later to complete and review this.
- `Prequalify` - is one of the many steps of this registration form
  - GraphQL query to a CMS to find out what form fields should be displayed on this step.   

### Wordpress
- Redacted (for confidentiality and propietary reasons) and slimmed down plugins/themes that demonstrate knowledge of Wordpress and general PHP coding concepts. These are not meant to work by themselves.
- [Gutenberg plugin](wordpress/plugins/everside-gutenberg) that creates a `hero` block, with other components that support various attributes/functionality like [background colors](wordpress/plugins/everside-gutenberg/src/components/background-colors) or adding [images](wordpress/plugins/everside-gutenberg/src/components/image-editor)
- sample [theme](wordpress/themes/everside), originally created from the [sage theme](https://roots.io/sage/), which demonstrates a typical CPT, [clinic](wordpress/themes/everside/app/functions/Clinics.php). 
  - As well as integration with [Salesforce](wordpress/themes/everside/app/functions/Salesforce.php), to pull and sync data to the Wordpress site
  - Views are located [here](wordpress/themes/everside/resources/views) 

import Controller from '@ember/controller';
import { inject as service } from '@ember/service';

export default Controller.extend({
  session: service(),

  actions: {
    /**
     *
     */
    authenticate() {
      let {identification, password} = this.getProperties('identification', 'password');
      this.session.authenticate(
        'authenticator:oauth2',
        identification,
        password, [], {Authorization: 'Basic ' + window.base64.encode(identification + ':' + password)}).catch((reason) => {
        this.set('errorMessage', reason.error || reason);
      });
    }
  }
});

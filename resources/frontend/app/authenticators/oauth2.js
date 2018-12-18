import OAuth2PasswordGrant from 'ember-simple-auth/authenticators/oauth2-password-grant';

/**
 *
 * @returns {String}
 */
function apiClientId() {
  const selector = document.querySelector('meta[name="api-client-id"]');

  return selector.getAttribute('content');
}

export default OAuth2PasswordGrant.extend({
  serverTokenEndpoint: `/oauth/token`,
  serverTokenRevocationEndpoint: `/oauth/revoke`,
  sendClientIdAsQueryParam: true,
  clientSecret: 'VmPdeZQA1cz2U9IGqZU2kYzL071Awl0CM73BiB29',
  clientId: apiClientId(),
  scope: ''
});

## Propaget Api Definitions

Outlining the basic API endpoints for reference. For GET and POST requests "/?access_token={}" will be passed in the header.
The user object will be extracted from the access_token on the server end.

### Server info
GET: /info/?client_version={1.0}

    JSON:
    {
        server_version: 1.0
    }


### Get User Access Token

POST:
/token

     params:
           grant_type: password
           username: kookoo@propaget.com
           password: 123
           client_id: propaget-test

           OR

           grant_type: facebook
           fb_access_token: abcdefghABCDEFGH
           client_id: propaget-test


    JSON:
    {
        access_token: "1b886f4ff76b44c13f1be520278d421bced9445c"
        expires_in: 3600
        token_type: "Bearer"
        scope: null
        refresh_token: "771c8ed9fbeeddc266c78f47f0278e3d2e325fb3"
    }


### Client
* ###### get client information

  GET: /client

* ###### update client information

  PUT: /client

      params:
          name1=value1
        & name2=value2  



### Agent
* ###### get agent information
GET: /agent

* ###### update agent information
PUT: /agent

        params:
            name1=value1
          & name2=value2  



### Distribution List

* ###### get distribution list information
GET: /agent/distribution_list/{id}

* ###### update distribution list information
PUT: /agent/distribution_list/{id}

        params:
            name1=value1
          & name2=value2  

* ###### create a new distribution list
POST: /agent/distribution_list

        params:
            name1=value1
          & name2=value2  

* ###### delete a distribution list
DELETE: /agent/distribution_list/{id}

* ###### get saved distribution lists for an agent
GET: /agent/distribution_lists



### Requirement
* ###### get requirement details
GET: /requirement/{id}


* ###### create a new requirement
POST: /requirement

        params:
            name1=value1
          & name2=value2  

* ###### update requirement details
PUT: /requirement/{id}

        params:
            name1=value1
          & name2=value2  

* ###### get all (drafts + handshaken) requirement cards of an agent
GET: /agent/requirements

* ###### get only draft-requirement cards of an agent
GET: /agent/requirements?type=draft

* ###### get only handshaken-requirement cards of an agent
GET: /agent/requirements?type=handshake


### Property
* ###### get property details
GET: /property/{id}

* ###### create a new property
POST: /property

        params:
            name1=value1
          & name2=value2  

* ###### update property details
PUT: /property/{id}

        params:
            name1=value1
          & name2=value2

* ###### get all (drafts + handshaken) property cards of an agent
GET: /agent/properties

* ###### get only draft-property cards of an agent
GET: /agent/properties?type=draft

* ###### get only handshaken-property cards of an agent
GET: /agent/properties?type=handshake



### Notifications
* ###### get unread notifications
GET: /agent/notifications

* ###### get all notifications
GET: /agent/notifications?all=true



### Forward (Propaget)
* ###### forward property tile to a distribution list or a single agent
POST: /agent/forward/property/{id}

        params:
            up_agent_id = xxx
          & property_id = xxx
          & distribution_list_id = xxx
          OR
          down_agent_id = xxx

* ###### forward requirement tile to a distribution list or a single agent
POST: /agent/forward/requirement/{id}

        params:
            up_agent_id = xxx
          & requirement_id = xxx
          & distribution_list_id = xxx
          OR
          down_agent_id = xxx



### Handshake

* ###### get all handshakes for agent
GET: /agent/handshakes

* ###### get all pending handshakes for agent
GET: /agent/handshakes?pending=true

* ###### get all handshakes for client
GET: /client/handshakes

* ###### get all pending handshakes for client
GET: /client/handshakes?pending=true

* ###### get all pending handshakes for a property (could be used in top level agent and client dashboard)
GET: /property/{property_id/}handshakes?pending=true

* ###### get all pending handshakes for a requirement (could be used in top level agent and client dashboard)
GET: /requirement/{requirement_id}/handshakes?pending=true

* ###### confirm a received handshake
POST: /handshake/{network_chain_id}?confirm=true

        params:
              network_chain_id: xxx
            & up_agent_id: xxx
            & dwn_agent_id: xxx
            & requirement_id: xxx
            OR
            & property_id: xxx



### Matches
* ###### get matches for requirement - for an agent
GET: /matches/requirement/{id}

        params:
            agent_id: xxx

        returns (array of properties):
          {
            [
              prop1, prop2, prop3, ... propN
            ]
          }

* ###### get matches for property - for an agent
GET: /matches/property/{id}

        params:
            agent_id: xxx

        returns (array of requirements):
          {
            [
              req1, req2, req3, ... reqN
            ]
          }

* ###### confirm match
POST: /confirm_match

        params:
              agent_id: xxx
            & property_id: xxx
            & requirement_id: xxx

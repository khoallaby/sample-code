/*
* This is part of a react project, for gathering user input. As part of a multi step registration form.
* Data is saved into Firebase
*/

import React, { useEffect, useContext } from 'react';
import { Router } from '@reach/router';
import {
  Layout,
  Login,
  Prequalify,
  PrequalifyResult,
  HouseholdAddress,
  HouseholdNormalize,
  HouseholdDuplicate,
  LandlordAddress,
  LandlordNormalize,
  LandlordSelect,
  SubmitSuccess,
  SubmitFail,
  NotFoundPage,
} from '../components';
import { UserContext } from '../context/user-context';
import { Firebase } from '../services/firebase';
import firebase from 'firebase/app';
import 'firebase/database';
import 'firebase/auth';

if (typeof window !== `undefined`) {
  !firebase.apps.length && Firebase();
}

const App = () => {
  const { setIsSignedIn, setUser } = useContext(UserContext);

  useEffect(() => {
    const handleSignIn = (user) => {
      const { uid } = user;
      const db = firebase.database();
      const userRef = db.ref('users/' + uid);
      userRef.once('value', (snapshot) => {
        if (snapshot.exists()) {
          const newUser = snapshot.val();
          setUser(newUser);
          setIsSignedIn(true);
        }
      });
    };

    const onAuthStateChange = () => {
      return firebase.auth().onAuthStateChanged((user) => {
        if (user) {
          handleSignIn(user);
        } else {
          setIsSignedIn(false);
        }
      });
    };
    const unsubscribe = onAuthStateChange();
    return () => {
      unsubscribe();
    };
  }, [setIsSignedIn, setUser]);

  return (
    <Layout>
      <Router>
        <Login path="/app/login" />
        <Prequalify path="/app/prequalify" />
        <PrequalifyResult path="/app/prequalify-result" />
        <HouseholdAddress path="/app/household-address" />
        <HouseholdNormalize path="/app/household-normalize" />
        <HouseholdDuplicate path="/app/household-duplicate" />
        <LandlordAddress path="/app/landlord-address" />
        <LandlordNormalize path="/app/landlord-normalize" />
        <LandlordSelect path="/app/landlord-select" />
        <SubmitSuccess path="/app/submit-success" />
        <SubmitFail path="/app/submit-fail" />
        <NotFoundPage path="/app/404" />
      </Router>
    </Layout>
  );
};
export default App;

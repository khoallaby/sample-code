/*
* This is one of the steps in the registration process
* It queries Contentful's API to retrieve the form fields that will be displayed on this page.
* Contentful is a CMS, where the admin can create the form fields that will be displayed on all the steps/pages throughout the site
*/

import React, { useContext, useEffect, useState } from 'react';
import { Typography, Grid, Box, Divider, makeStyles } from '@material-ui/core';
import { UserContext } from '../context/user-context';
import { useStaticQuery, graphql, navigate } from 'gatsby';
import {
  DropdownQuestion,
  RadioQuestion,
  InputQuestion,
  CustomButton as Button,
} from './index';
import theme from '../styles/theme';
import { incomeDisqualified } from '../utils/helpers';

const PrequalQuery = graphql`
  query PrequalifyPage {
    contentfulPage(title: { eq: "Prequalify" }) {
      title
      header
      description {
        description
      }
      sections {
        title
        question {
          __typename
          ...DropdownQuestion
          ...RadioQuestion
          ...InputQuestion
        }
      }
    }
  }
`;

const useStyles = makeStyles((theme) => ({
  prequalContainer: {
    margin: '27px auto',
    maxWidth: '684px',
    padding: '33px 42px',
    boxShadow: '0px 4px 8px rgba(91, 91, 91, 0.1)',
    backgroundColor: '#fff',
  },
  [theme.breakpoints.down('sm')]: {
    prequalContainer: {
      margin: '20px auto',
    },
  },
}));

export const Prequalify = () => {
  const classes = useStyles(theme);
  const data = useStaticQuery(PrequalQuery);
  const { contentfulPage } = data;
  const {
    header,
    description: { description },
    sections,
  } = contentfulPage;

  const prequalInitialState = sections[0].question.reduce(
    (acc, { databaseField, acceptance }) => {
      acc = {
        ...acc,
        [databaseField]: {
          value: '',
          acceptance: acceptance ? acceptance[0] : null,
          error: false,
        },
      };
      return acc;
    },
    {}
  );
  const { user, setUser, prequal, setPrequal } = useContext(UserContext);

  useEffect(() => {
    setPrequal(prequalInitialState);
  }, []);

  const [error, setError] = useState(false);

  const checkPrequal = () => {
    const keys = Object.keys(prequal);

    const unaccepted = keys.find((key) => {
      return (
        prequal[key].acceptance &&
        prequal[key].value !== prequal[key].acceptance
      );
    });

    const {
      APP_STATE: state,
      APP_COUNTY: county,
      HH_NUM: householdMembers,
      HH_MON_INC_AMT_CURR: income,
    } = prequal;

    const incomeDisQual = incomeDisqualified(
      state.value,
      county.value,
      householdMembers.value,
      income.value
    );

    const disqualified = incomeDisQual || unaccepted;
    const formLocation = state.value.toLowerCase();

    !disqualified &&
      setUser({ ...user, isPrequalified: true, formLocation: formLocation });
    disqualified && setUser({ ...user, isPrequalified: false });
    navigate('/app/prequalify-result');
  };

  const handleSubmit = () => {
    const values = Object.values(prequal);
    const keys = Object.keys(prequal);
    const incomplete = values.filter((value, i) => {
      if (value.value === '') {
        const key = [keys[i]];
        setPrequal((prequal) => ({
          ...prequal,
          [key]: { ...prequal[key], error: true },
        }));
      }
      return value.value === '';
    });

    incomplete.length ? setError(true) : checkPrequal();
  };

  const renderQuestion = (question, questionNumber) => {
    const questions = {
      ContentfulDropdownQuestion: (
        <DropdownQuestion
          question={question}
          key={question.id}
          value={prequal}
          setValue={setPrequal}
          questionNumber={questionNumber + 1}
        />
      ),
      ContentfulRadioQuestion: (
        <RadioQuestion
          question={question}
          key={question.id}
          value={prequal}
          setValue={setPrequal}
          questionNumber={questionNumber + 1}
        />
      ),
      ContentfulInputQuestion: (
        <InputQuestion
          question={question}
          key={question.id}
          value={prequal}
          setValue={setPrequal}
          questionNumber={questionNumber + 1}
        />
      ),
    };

    return questions[question.__typename];
  };
  return (
    <Grid
      container
      direction="column"
      justify="center"
      className={classes.prequalContainer}
    >
      <Grid item style={{ marginBottom: '15px' }}>
        <Typography variant="h2" color="primary">
          {header}
        </Typography>
      </Grid>
      <Grid item>
        <Typography variant="body2">{description}</Typography>
      </Grid>
      <Divider style={{ marginTop: '20px', marginBottom: '20px' }} />
      <Typography style={{ marginBottom: '30px' }} variant="subtitle2">
        *Indicates a Required Field
      </Typography>

      {sections.map((section) => {
        return section.question.map((question, i) => {
          return renderQuestion(question, i);
        });
      })}
      <Box style={{ marginTop: '30px' }}>
        {error && (
          <Typography
            variant="subtitle2"
            color="error"
            style={{ marginBottom: '10px' }}
          >
            Please fill out all required fields
          </Typography>
        )}
        <Button
          onClick={handleSubmit}
          variant="contained"
          color="primary"
          width="95px"
          dataQa="button-submit"
        >
          <Typography variant="button" display="block">
            Submit
          </Typography>
        </Button>
      </Box>
    </Grid>
  );
};

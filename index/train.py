# coding:utf-8
from __future__ import print_function

import os
import numpy as np
import tensorflow as tf
import tflearn
from sklearn.neural_network import MLPClassifier
from tflearn.layers.core import input_data, dropout, fully_connected
from tflearn.layers.conv import conv_1d, global_max_pool
from tflearn.layers.merge_ops import merge
from tflearn.layers.estimator import regression
from tflearn.data_utils import to_categorical, pad_sequences

from utils import recursion_load_php_file_opcode
from sklearn.model_selection import train_test_split
from sklearn.naive_bayes import GaussianNB
from sklearn import metrics
from sklearn.externals import joblib

os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'

max_document_length = 100


def prepare_data():
    """
    生成需要使用的数据，写入文件后，以供后面应用
    :return:
    """
    # 生成数据并写入文件
    #global max_document_length
    if os.path.exists('white_opcodes.txt') is False:
        print ('[Info] White opcodes doesnt exists ... generating opcode ..')
        white_opcodes_list = recursion_load_php_file_opcode('.\\white-list\\')
        with open('white_opcodes.txt', 'w') as f:
            for line in white_opcodes_list:
                f.write(line + '\n')
    else:
        print ('[Info] White opcodes exists')

    if os.path.exists('black_opcodes.txt') is False:
        black_opcodes_list = recursion_load_php_file_opcode('.\\black-list\\')
        with open('black_opcodes.txt', 'w') as f:
            for line in black_opcodes_list:
                f.write(line + '\n')
    else:
        print ('[Info] black opcodes exists')

    # 使用数据

    white_file_list = []
    black_file_list = []

    with open('black_opcodes.txt', 'r') as f:
        for line in f:
            black_file_list.append(line.strip('\n'))

    with open('white_opcodes.txt', 'r') as f:
        for line in f:
            white_file_list.append(line.strip('\n'))

    len_white_file_list = len(white_file_list)
    len_black_file_list = len(black_file_list)

    y_white = [0] * len_white_file_list
    y_black = [1] * len_black_file_list

    x = white_file_list + black_file_list
    y = y_white + y_black

    print('[Data status] ... ↓')
    print('[Data status] dataset length : {}'.format(len_white_file_list + len_black_file_list))
    print('[Data status] White list length : {}'.format(len_white_file_list))
    print('[Data status] black list length : {}'.format(len_black_file_list))
    # X raw data
    # y label
    vp = tflearn.data_utils.VocabularyProcessor(max_document_length=max_document_length,
                                                min_frequency=0,
                                                vocabulary=None,
                                                tokenizer_fn=None)
    x = vp.fit_transform(x, unused_y=None)
    x = np.array(list(x))

    return x, y


def do_mlp():
    print("MLP and opcode")
    # mlp
    clf = MLPClassifier(solver='adam',
                        activation='relu',
                        learning_rate_init=0.001,
                        batch_size=100)

    # print clf
    x, y = prepare_data()
    x_train, x_test, y_train, y_test = train_test_split(x, y, test_size=0.4, random_state=0)
    clf.fit(x_train, y_train)
    joblib.dump(clf, "save/mlp.pkl")
    y_pred = clf.predict(x_test)

    do_metrics(y_test, y_pred)


def do_cnn():
    #global max_document_length
    print("CNN and opcode")
    x, y = prepare_data()

    trainX, testX, trainY, testY = train_test_split(x, y, test_size=0.4, random_state=0)
    y_test = testY

    trainX = pad_sequences(trainX, maxlen=max_document_length, value=0.)
    testX = pad_sequences(testX, maxlen=max_document_length, value=0.)
    # Converting labels to binary vectors
    trainY = to_categorical(trainY, nb_classes=2)
    testY = to_categorical(testY, nb_classes=2)

    # Building convolutional network
    network = input_data(shape=[None, max_document_length], name='input')
    network = tflearn.embedding(network, input_dim=1000000, output_dim=128)
    branch1 = conv_1d(network, 128, 3, padding='valid', activation='relu', regularizer="L2")
    branch2 = conv_1d(network, 128, 4, padding='valid', activation='relu', regularizer="L2")
    branch3 = conv_1d(network, 128, 5, padding='valid', activation='relu', regularizer="L2")
    network = merge([branch1, branch2, branch3], mode='concat', axis=1)
    network = tf.expand_dims(network, 2)
    network = global_max_pool(network)
    network = dropout(network, 0.8)
    network = fully_connected(network, 2, activation='softmax')
    network = regression(network, optimizer='adam', learning_rate=0.001,
                         loss='categorical_crossentropy', name='target')

    model = tflearn.DNN(network, tensorboard_verbose=0)

    model.fit(trainX, trainY, n_epoch=5, shuffle=True, validation_set=0.1,
              show_metric=True, batch_size=100, snapshot_epoch=False,snapshot_step=None, run_id="webshell")
    model.save("save/cnn.tfl")

    y_predict_list = model.predict(testX)

    y_predict = []
    for i in y_predict_list:
        # print i[0]
        if i[0] > 0.5:
            y_predict.append(0)
        else:
            y_predict.append(1)

    do_metrics(y_test, y_predict)


def do_metrics(y_test, y_pred):
    print ("metrics.accuracy_score:")
    print (metrics.accuracy_score(y_test, y_pred))
    print ("metrics.confusion_matrix:")
    print (metrics.confusion_matrix(y_test, y_pred))


def main():
    #do_mlp()
    do_cnn()


if __name__ == '__main__':
    main()
